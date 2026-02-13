"use server";

import { db } from "@/lib/db";
import { model } from "@/lib/gemini";
import { unstable_noStore as noStore } from "next/cache";

export async function getTeacherDashboardSummary() {
    noStore(); // Disable caching for real-time results

    try {
        // 1. Fetch recent mood logs (last 7 days)
        const sevenDaysAgo = new Date();
        sevenDaysAgo.setDate(sevenDaysAgo.getDate() - 7);

        const logs = await db.moodLog.findMany({
            where: {
                createdAt: {
                    gte: sevenDaysAgo,
                },
            },
            include: {
                user: {
                    select: {
                        name: true,
                    },
                },
            },
            orderBy: {
                createdAt: "desc",
            },
            take: 50, // Limit to 50 entries to save tokens
        });

        if (logs.length === 0) {
            return "Belum ada data mood siswa yang cukup untuk dianalisis minggu ini.";
        }

        // 2. Format data for the prompt
        const logSummary = logs
            .map(
                // eslint-disable-next-line @typescript-eslint/no-explicit-any
                (log: any) =>
                    `- ${log.user.name}: ${log.mood} (${log.note || "No note"})`
            )
            .join("\n");

        const prompt = `
      Sebagai asisten AI untuk guru BK (Bimbingan Konseling), berikan ringkasan singkat (maksimal 2-3 kalimat) mengenai kondisi mental kelas berdasarkan log mood siswa seminggu terakhir berikut ini.
      Fokus pada tren umum dan hal-hal yang perlu diperhatikan. Jangan sebutkan nama siswa secara spesifik dalam ringkasan publik ini, gunakan istilah umum seperti "beberapa siswa" atau "sebagian besar".
      Gunakan Bahasa Indonesia yang profesional namun empatik.

      Data Log Mood:
      ${logSummary}
    `;

        // 3. Generate summary using Gemini
        const result = await model.generateContent(prompt);
        const response = await result.response;
        const text = response.text();

        return text;
    } catch (error) {
        console.error("Error generating AI summary:", error);
        return "Maaf, fitur analisis AI sedang tidak dapat diakses saat ini.";
    }
}

// ... (imports remain same)

export async function analyzeJournalContent(content: string) {
    noStore();
    try {
        if (!process.env.GEMINI_API_KEY && !process.env.GOOGLE_GEMINI_API_KEY) {
            throw new Error("API Key is missing. Please check .env file.");
        }

        const prompt = `
            Sebagai psikolog sekolah yang profesional dan empatik, analisislah jurnal siswa berikut ini.
            Berikan respon HANYA dalam format JSON valid (tanpa markdown code block, tanpa penjelasan tambahan) dengan struktur:
            {
                "emotion": "Emosi dominan (misal: Happy, Sad, Anxious, Angry, Neutral)",
                "riskLevel": "Level risiko (Low, Medium, High)",
                "summary": "Ringkasan singkat 1 kalimat",
                "suggestions": ["Saran 1", "Saran 2", "Saran 3"]
            }

            Isi Jurnal:
            "${content}"
        `;

        const result = await model.generateContent(prompt);
        const response = await result.response;
        let text = response.text();

        // Robust cleanup for markdown code blocks
        text = text.replace(/```json/g, "").replace(/```/g, "").trim();

        // Try to parse JSON
        try {
            return JSON.parse(text);
        } catch {
            console.error("JSON Parse Error. Raw text:", text);
            // Fallback: simple regex extraction if JSON fails
            return {
                emotion: "Unknown",
                riskLevel: "Low",
                summary: "Gagal memproses analisis otomatis. Silakan coba lagi.",
                suggestions: []
            };
        }
    } catch (error: unknown) {
        console.error("Error analyzing journal:", error);
        return {
            error: true,
            message: (error as Error).message || "Terjadi kesalahan saat menghubungi AI."
        };
    }
}
