from flask import Flask, request, jsonify
import joblib

app = Flask(__name__)

# Load trained AI model (Pipeline includes both Vectorizer + Model)
model = joblib.load("disease_model.pkl")

@app.route("/predict", methods=["POST"])
def predict():
    try:
        data = request.json
        symptoms = data.get("symptoms")

        if not symptoms:
            return jsonify({"error": "No symptoms provided"}), 400

        # Get probabilities for all possible diseases
        prediction_probs = model.predict_proba([symptoms])[0]

        # Get the top 3 predictions
        top_indices = prediction_probs.argsort()[-3:][::-1]  # Get indices of top 3 probabilities
        top_diseases = model.classes_[top_indices]
        top_confidences = prediction_probs[top_indices] * 100  # Convert to percentage

        # Format response
        predictions = [
            {"disease": disease, "confidence": f"{round(confidence, 2)}%"}
            for disease, confidence in zip(top_diseases, top_confidences)
        ]

        return jsonify({"predictions": predictions})

    except Exception as e:
        return jsonify({"error": str(e)}), 500

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=True)
