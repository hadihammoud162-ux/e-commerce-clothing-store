from transformers import pipeline

# Load the summarization pipeline once
summarizer = pipeline("summarization", model="facebook/bart-large-cnn")

def summarize_text(text):
    # You can tweak max_length and min_length as needed
    summary = summarizer(text, max_length=100000, min_length=20, do_sample=False)
    return summary[0]['summary_text']

def main():
    print("Enter the text you want to summarize (press Enter twice to finish):")
    # Read multiline input until empty line is entered
    lines = []
    while True:
        line = input()
        if line.strip() == "":
            break
        lines.append(line)
    user_text = "\n".join(lines)

    if user_text.strip() == "":
        print("No input provided. Exiting.")
        return

    print("\nGenerating summary...\n")
    summary = summarize_text(user_text)
    print("Summary:")
    print(summary)

if __name__ == "__main__":
    main()
