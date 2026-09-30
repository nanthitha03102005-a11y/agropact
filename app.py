import os
from flask import Flask, request, jsonify
from datetime import datetime
import random

app = Flask(__name__)

# Mock Model Registry
MODELS = {
    "crop_quality": {
        "version": "tomato_quality_v2",
        "type": "CNN",
        "precision": "94.2%"
    },
    "crop_price": {
        "version": "price_intelligence_xgb_v1",
        "type": "XGBoost",
        "rmse": "1.45"
    }
}

@app.route('/api/ai/health', methods=['GET'])
def health_check():
    """Health check for PHP backend to verify AI service is online."""
    return jsonify({"status": "AI Inference Engine Online", "models": MODELS}), 200

@app.route('/api/ai/predict_quality', methods=['POST'])
def predict_quality():
    """
    Simulates a CNN image classification for Crop Quality & Disease
    Expects File input (image) and form data (crop)
    """
    if 'image' not in request.files:
        return jsonify({"error": "No image provided"}), 400
        
    crop = request.form.get('crop', 'Unknown')
    
    # In a real model, we would pass request.files['image'] to model.predict()
    # Here we mock the result of the CNN inference mathematically.
    confidence = round(random.uniform(75.5, 96.8), 2)
    
    # Mocking Disease Detection based on crop
    diseases = {
        'Tomato': ['Early Blight', 'Late Blight', 'Healthy', 'Leaf Mold'],
        'Onion': ['Neck Rot', 'Black Mold', 'Healthy'],
    }
    
    detected = random.choice(diseases.get(crop, ['Healthy', 'Unknown Pathogen']))
    grade = 'Grade A' if detected == 'Healthy' and confidence > 90 else 'Grade B'
    
    result = {
        "model": MODELS["crop_quality"]["version"],
        "analyzed_at": datetime.now().isoformat(),
        "crop": crop,
        "predicted_grade": grade,
        "disease_detected": detected,
        "confidence": confidence,
        "recommendation": "Maintain optimal storage temperature." if detected == 'Healthy' else "Isolate affected batch to prevent fungal spread. Consult local agronomist.",
        "warning": "Low confidence (<85%). Manual verification strongly recommended." if confidence < 85 else ""
    }
    
    return jsonify(result), 200

@app.route('/api/ai/predict_price', methods=['POST'])
def predict_price():
    """
    Simulates XGBoost Price Prediction
    Expects JSON data (crop, grade, quantity, region, month)
    """
    data = request.json
    if not data:
        return jsonify({"error": "Invalid JSON input"}), 400
        
    crop = data.get('crop', 'Unknown')
    grade = data.get('grade', 'Grade B')
    
    # Base mocked prices
    base_prices = {'Tomato': 30, 'Onion': 25, 'Banana': 40}
    base = base_prices.get(crop, 20)
    
    # Grade multiplier
    multiplier = 1.2 if grade == 'Grade A' else 0.9
    
    predicted_val = base * multiplier
    
    # Adding margin of error
    low_est = round(predicted_val - random.uniform(1.0, 3.0), 2)
    high_est = round(predicted_val + random.uniform(1.0, 5.0), 2)
    
    result = {
        "model": MODELS["crop_price"]["version"],
        "crop": crop,
        "grade": grade,
        "estimated_price_range": {
            "low": low_est,
            "expected": round(predicted_val, 2),
            "high": high_est
        },
        "market_trend": "Upward" if random.choice([True, False]) else "Stable",
        "confidence": round(random.uniform(88.0, 95.0), 2),
        "warning": "Indicative estimate only based on historical aggregated data."
    }
    
    return jsonify(result), 200

if __name__ == '__main__':
    print("🚀 Starting AGRI-PACT Python Local Inference Engine...")
    app.run(host='127.0.0.1', port=5000, debug=True)
