"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";
import { ShieldAlert, Loader2 } from "lucide-react";

export default function RiskAssessmentButton({ studentId }: { studentId: string }) {
    const [loading, setLoading] = useState(false);
    const router = useRouter();

    const handleRun = async () => {
        if (!confirm("Jalankan analisis risiko AI untuk siswa ini? Ini akan menggunakan kuota token.")) return;

        setLoading(true);
        try {
            const res = await fetch("/api/risk-assessment", {
                method: "POST",
                body: JSON.stringify({ studentId }),
                headers: { "Content-Type": "application/json" }
            });

            if (!res.ok) throw new Error("Failed");

            alert("Analisis selesai!");
            router.refresh();
        } catch {
            alert("Gagal menjalankan analisis.");
        } finally {
            setLoading(false);
        }
    };

    return (
        <button
            onClick={handleRun}
            disabled={loading}
            className="flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 disabled:opacity-50"
        >
            {loading ? <Loader2 className="animate-spin" size={18} /> : <ShieldAlert size={18} />}
            {loading ? "Analyzing..." : "Run AI Assessment"}
        </button>
    );
}
