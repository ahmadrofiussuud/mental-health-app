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
