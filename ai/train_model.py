import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.naive_bayes import MultinomialNB
from sklearn.pipeline import Pipeline
import joblib
import os

print("Starting AI Model Training...\n")

# ------------------- TRAINING THE DISEASE PREDICTION MODEL -------------------
try:
    dataset_path = "dataset.csv"
    df = pd.read_csv(dataset_path)
    print(f"Dataset loaded from {dataset_path}")

    # Identify symptom columns dynamically
    symptom_columns = [col for col in df.columns if col.startswith("Symptom_")]

    if "Disease" not in df.columns or not symptom_columns:
        raise ValueError("CSV file must contain 'Disease' and at least one 'Symptom_' column.")

    print("All required columns found.")

    # Combine all symptom columns into a single text column
    df["All_Symptoms"] = df[symptom_columns].astype(str).agg(" ".join, axis=1)

    # Drop any unnecessary columns
    df = df[["Disease", "All_Symptoms"]].dropna()
    print(f"Removed empty rows. {len(df)} samples remaining.")

    # Define feature extraction and model pipeline
    disease_pipeline = Pipeline([
        ("vectorizer", TfidfVectorizer()),  # TF-IDF for better text processing
        ("classifier", MultinomialNB())  # Naive Bayes classifier
    ])

    # Train the disease prediction model
    X_disease = df["All_Symptoms"]
    y_disease = df["Disease"]
    disease_pipeline.fit(X_disease, y_disease)
    print(f"Disease Model trained with {len(df)} samples.")

    # Save the trained disease model
    disease_model_filename = "disease_model.pkl"
    joblib.dump(disease_pipeline, disease_model_filename)
    print(f"Disease Model saved successfully as {disease_model_filename}")

except Exception as e:
    print(f"\nDisease Model Training Failed: {str(e)}")

# ------------------- TRAINING THE MEDICINE RECOMMENDATION MODEL -------------------
try:
    medicine_dataset_path = "medicine_dataset.csv"

    # Check if the file exists
    if not os.path.exists(medicine_dataset_path):
        raise FileNotFoundError(f"Medicine dataset file '{medicine_dataset_path}' not found!")

    med_df = pd.read_csv(medicine_dataset_path)
    print(f"Medicine dataset loaded from {medicine_dataset_path}")

    if "Disease" not in med_df.columns or "Medicine" not in med_df.columns:
        raise ValueError("CSV file must contain 'Disease' and 'Medicine' columns.")

    print("All required columns found in medicine dataset.")

    # Convert medicines into a single string for each disease
    med_df = med_df.groupby("Disease")["Medicine"].apply(lambda x: " ".join(x)).reset_index()

    # Define feature extraction and model pipeline for medicine recommendation
    medicine_pipeline = Pipeline([
        ("vectorizer", TfidfVectorizer()),  # TF-IDF for better text processing
        ("classifier", MultinomialNB())  # Naive Bayes classifier
    ])

    # Train the medicine recommendation model
    X_medicine = med_df["Disease"]
    y_medicine = med_df["Medicine"]
    medicine_pipeline.fit(X_medicine, y_medicine)
    print(f"Medicine Model trained with {len(med_df)} samples.")

    # Save the trained medicine model
    medicine_model_filename = "medicine_model.pkl"
    joblib.dump(medicine_pipeline, medicine_model_filename)
    print(f"Medicine Model saved successfully as {medicine_model_filename}")

except Exception as e:
    print(f"\nMedicine Model Training Failed: {str(e)}")
