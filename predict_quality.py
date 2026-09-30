import json
import sys
import random

def predict_quality(image_path, crop_id):
    # In a real scenario, we load a pre-trained ML model here
    # model = load_model('path/to/models/tomato_quality_v1.pkl')
    # prediction = model.predict(image_path)
    
    # For demonstration/development purposes:
    # Simulating AI evaluation based on crop_id
    
    grades = ['Grade A', 'Grade B', 'Grade C', 'Damaged']
    weights = [0.6, 0.3, 0.08, 0.02]
    
    pred_grade = random.choices(grades, weights)[0]
    confidence = round(random.uniform(0.80, 0.98), 4)
    visual_score = round(random.uniform(70, 95), 2)
    
    explanation = ["Good colour", "Uniform appearance", "Low visible damage"]
    
    if pred_grade == 'Grade B' or pred_grade == 'Grade C':
        explanation = ["Minor surface defects", "Slight color inconsistency"]
        
    result = {
        "success": True,
        "prediction": pred_grade,
        "confidence": confidence,
        "visual_score": visual_score,
        "model_version": f"model_v1_crop_{crop_id}",
        "explanation": explanation
    }
    
    return json.dumps(result)

if __name__ == "__main__":
    if len(sys.argv) < 3:
        print(json.dumps({"success": False, "message": "Usage: predict_quality.py <image_path> <crop_id>"}))
        sys.exit(1)
        
    image_path = sys.argv[1]
    crop_id = sys.argv[2]
    
    output = predict_quality(image_path, crop_id)
    print(output)
