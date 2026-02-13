const { GoogleGenerativeAI } = require("@google/generative-ai");
require("dotenv").config({ path: ".env" });

async function listModels() {
    const apiKey = process.env.GEMINI_API_KEY || process.env.GOOGLE_GEMINI_API_KEY;
    if (!apiKey) {
        console.error("No API key found in .env");
        return;
    }

    const genAI = new GoogleGenerativeAI(apiKey);

    try {
        // For listing models, we act directly on the class or use a manager if available in newer SDKs
        // Inspecting the SDK, usually it's not directly on the instance for some versions.
        // However, for 0.24.x, let's try to get a model and see if we can list from the client if possible
        // Actually, looking at docs for @google/generative-ai, there isn't a direct listModels on the client usually.
        // But we can try a fetch if the SDK doesn't expose it easily, OR assume we should try standard names.

        // WAIT, the error message literally says "Call ListModels".
        // In the Node SDK, it might be separate.
        // Let's try to use the REST API manually to be sure if the SDK is confusing.

        console.log("Checking API Key: " + apiKey.substring(0, 5) + "...");

        const response = await fetch(`https://generativelanguage.googleapis.com/v1beta/models?key=${apiKey}`);
        const data = await response.json();

        if (data.models) {
            console.log("Available Models:");
            data.models.forEach(m => {
                if (m.supportedGenerationMethods.includes("generateContent")) {
                    console.log(`- ${m.name} (Supports generateContent)`);
                } else {
                    console.log(`- ${m.name}`);
                }
            });
        } else {
            console.log("No models found or error:", data);
        }

    } catch (error) {
        console.error("Error:", error);
    }
}

listModels();
