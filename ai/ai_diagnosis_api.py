from flask import Flask, request, jsonify
from flask_cors import CORS
import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.naive_bayes import MultinomialNB
from sklearn.pipeline import Pipeline
import joblib
import os

app = Flask(__name__)
CORS(app)  # Enable CORS for frontend communication

# Path to save model
MODEL_PATH = "disease_model.pkl"

# Medicine recommendations (Modify as needed)
MEDICINE_RECOMMENDATIONS = {
    "Over Fatigue": ["Paracetamol", "Vitamin B Complex", "Hydration"],
    "Migraine": ["Ibuprofen", "Sumatriptan", "Caffeine"],
    "Flu": ["Paracetamol", "Antihistamine", "Cough Syrup"],
    "Anemia": ["Iron Supplements", "Vitamin C", "Folic Acid"]
}

# Function to train and save model
def train_model():
    print("🔹 Training AI Diagnosis Model...\n")

    try:
        dataset_path = "dataset.csv"
        if not os.path.exists(dataset_path):
            raise FileNotFoundError("❌ Dataset file not found. Please provide 'dataset.csv'.")

        df = pd.read_csv(dataset_path)
        symptom_columns = [col for col in df.columns if col.startswith("Symptom_")]

        if "Disease" not in df.columns or not symptom_columns:
            raise ValueError("❌ CSV must contain 'Disease' and at least one 'Symptom_' column.")

        df["All_Symptoms"] = df[symptom_columns].astype(str).agg(" ".join, axis=1)
        df = df[["Disease", "All_Symptoms"]].dropna()

        pipeline = Pipeline([
            ("vectorizer", TfidfVectorizer()), 
            ("classifier", MultinomialNB())  
        ])

        pipeline.fit(df["All_Symptoms"], df["Disease"])
        joblib.dump(pipeline, MODEL_PATH)

        print(f"✅ Model training complete. {len(df)} samples used.")
    except Exception as e:
        print(f"\n❌ Training failed: {str(e)}")

# Load or train model
if os.path.exists(MODEL_PATH):
    print("🔹 Loading existing AI model...\n")
    model = joblib.load(MODEL_PATH)
else:
    train_model()
    model = joblib.load(MODEL_PATH)

# API Endpoint for AI Diagnosis
@app.route("/predict", methods=["POST"])
def predict():
    try:
        data = request.json
        symptoms = data.get("symptoms")

        if not symptoms:
            return jsonify({"error": "No symptoms provided"}), 400

        prediction_probs = model.predict_proba([symptoms])[0]
        top_indices = prediction_probs.argsort()[-3:][::-1]
        top_diseases = model.classes_[top_indices]

        predictions = [{"disease": disease} for disease in top_diseases]
        return jsonify({"predictions": predictions})

    except Exception as e:
        return jsonify({"error": str(e)}), 500

# API Endpoint for Medicine Recommendation
@app.route("/recommend", methods=["POST"])
def recommend():
    try:
        data = request.json
        confirmed_disease = data.get("disease")

        if not confirmed_disease:
            return jsonify({"error": "No disease provided"}), 400

        medicines = MEDICINE_RECOMMENDATIONS.get(confirmed_disease, ["Consult a doctor for proper medication."])
        return jsonify({"medicines": medicines})

    except Exception as e:
        return jsonify({"error": str(e)}), 500

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=True)
