import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.naive_bayes import MultinomialNB
from sklearn.pipeline import Pipeline
import joblib

print("🔹 Starting AI Model Training...\n")

try:
    # Load dataset from CSV
    dataset_path = "dataset.csv"
    df = pd.read_csv(dataset_path)
    print(f"📂 Dataset loaded from {dataset_path}")

    # Identify symptom columns dynamically
    symptom_columns = [col for col in df.columns if col.startswith("Symptom_")]

    if "Disease" not in df.columns or not symptom_columns:
        raise ValueError("❌ CSV file must contain 'Disease' and at least one 'Symptom_' column.")

    print("✅ All required columns found.")

    # Combine all symptom columns into a single text column
    df["All_Symptoms"] = df[symptom_columns].astype(str).agg(" ".join, axis=1)

    # Drop any unnecessary columns
    df = df[["Disease", "All_Symptoms"]].dropna()

    print(f"🗑 Removed empty rows. {len(df)} samples remaining.")

    # Define feature extraction and model pipeline
    pipeline = Pipeline([
        ("vectorizer", TfidfVectorizer()),  # TF-IDF for better text processing
        ("classifier", MultinomialNB())  # Naive Bayes classifier
    ])

    # Train the model
    X = df["All_Symptoms"]
    y = df["Disease"]
    pipeline.fit(X, y)
    print(f"✅ Model training completed. {len(df)} samples used.")

    # Save the trained model
    model_filename = "disease_model.pkl"
    joblib.dump(pipeline, model_filename)
    print(f"💾 Model saved successfully as {model_filename}")

except Exception as e:
    print(f"\n❌ Training failed: {str(e)}")
