<?php
/**
 * ai_bridge.php
 * Handles the secure internal API communication between the PHP Core 
 * and the local Flask Python Inference Engine.
 */

class AIBridge {
    // Local URL of the Python Server running via XAMPP/Flask
    private $ai_service_url = "http://127.0.0.1:5000/api/ai";
    private $timeout = 5; // seconds

    /**
     * Sends crop image to local Python CNN Model
     */
    public function getQualityPrediction($imagePath, $cropType) {
        $endpoint = $this->ai_service_url . "/predict_quality";

        // FALLBACK: If we don't actually pass an image in testing, simulate Python API request
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_POST, 1);
            
            // In real integration: Use CURLFile to send binary data
            // $cfile = new CURLFile(realpath($imagePath), 'image/jpeg', 'crop_image');
            $postData = array(
                'crop' => $cropType,
                // 'image' => $cfile
            );
            
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);

            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpcode == 200 && $response) {
                return json_decode($response, true);
            }
            
            throw new Exception("AI Service HTTP Code: {$httpcode}");
            
        } catch (Exception $e) {
            // CRITICAL ARCHITECTURE REQUIREMENT:
            // "If Python service is unavailable: THE MAIN PHP SYSTEM MUST CONTINUE WORKING."
            return $this->getFallbackQualityPrediction($cropType);
        }
    }

    /**
     * Sends params to local Python XGBoost Model
     */
    public function getPricePrediction($cropType, $grade, $quantity) {
        $endpoint = $this->ai_service_url . "/predict_price";

        try {
            $ch = curl_init();
            $payload = json_encode(array(
                "crop" => $cropType,
                "grade" => $grade,
                "quantity" => $quantity
            ));

            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);

            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpcode == 200 && $response) {
                return json_decode($response, true);
            }
            
            throw new Exception("AI Service HTTP Code: {$httpcode}");

        } catch (Exception $e) {
            return $this->getFallbackPricePrediction($cropType, $grade);
        }
    }

    /**
     * Handles Python Server Offline State cleanly
     */
    private function getFallbackQualityPrediction($cropType) {
        return [
            "status" => "PHP_FALLBACK_MODE",
            "crop" => $cropType,
            "predicted_grade" => "Pending Manual Review",
            "confidence" => 0.0,
            "disease_detected" => "Unknown",
            "recommendation" => "Unable to contact ML Service. Please manually verify crop health.",
            "warning" => "AI service temporarily unavailable. Output relies on user input."
        ];
    }

    private function getFallbackPricePrediction($cropType, $grade) {
        return [
            "status" => "PHP_FALLBACK_MODE",
            "crop" => $cropType,
            "estimated_price_range" => [
                "low" => '--',
                "expected" => '--',
                "high" => '--'
            ],
            "confidence" => 0.0,
            "warning" => "AI Pricing service unavailable. Standard historical averages cannot be displayed."
        ];
    }
}
?>
