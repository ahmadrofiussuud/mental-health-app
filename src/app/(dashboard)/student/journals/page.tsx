"use client";

import { useState, useEffect } from "react";
import { useRouter } from "next/navigation";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";

interface Journal {
    id: string;
    title: string;
    content: string;
    mood: string;
    createdAt: string;
}

export default function StudentJournalsPage() {
    const router = useRouter();
    const [journals, setJournals] = useState<Journal[]>([]);
    const [loading, setLoading] = useState(true);
    const [formData, setFormData] = useState({
        title: "",
        content: "",
        mood: "NEUTRAL",
        isAnonymous: false,
    });

    useEffect(() => {
        fetch("/api/journals")
            .then((res) => res.json())
            .then((data) => {
                setJournals(data);
                setLoading(false);
            });
    }, []);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        await fetch("/api/journals", {
            method: "POST",
            body: JSON.stringify(formData),
            headers: { "Content-Type": "application/json" },
        });
        setFormData({ title: "", content: "", mood: "NEUTRAL", isAnonymous: false });
        router.refresh();
        // Re-fetch to update list locally for now
        const res = await fetch("/api/journals");
        setJournals(await res.json());
    };

    return (
        <div className="max-w-4xl mx-auto space-y-8">
            <Card>
                <CardHeader>
                    <CardTitle>Tulis Jurnal Baru</CardTitle>
                </CardHeader>
                <CardContent>
                    <form onSubmit={handleSubmit} className="space-y-4">
                        <div>
                            <label className="block text-sm font-medium">Judul</label>
                            <input
                                type="text"
                                className="w-full rounded border p-2"
                                value={formData.title}
                                onChange={(e) => setFormData({ ...formData, title: e.target.value })}
                                required
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium">Isi Jurnal</label>
                            <textarea
                                className="w-full rounded border p-2 h-32"
                                value={formData.content}
                                onChange={(e) => setFormData({ ...formData, content: e.target.value })}
                                required
                            />
                        </div>
                        <div className="flex gap-4">
                            <div className="flex-1">
                                <label className="block text-sm font-medium">Mood</label>
                                <select
                                    className="w-full rounded border p-2"
                                    value={formData.mood}
                                    onChange={(e) => setFormData({ ...formData, mood: e.target.value })}
                                >
                                    <option value="HAPPY">Happy 😄</option>
                                    <option value="CALM">Calm 😌</option>
                                    <option value="NEUTRAL">Neutral 😐</option>
                                    <option value="SAD">Sad 😢</option>
                                    <option value="ANGRY">Angry 😠</option>
                                </select>
                            </div>
                            <div className="flex items-end pb-2">
                                <label className="flex items-center gap-2 cursor-pointer">
                                    <input
                                        type="checkbox"
                                        checked={formData.isAnonymous}
                                        onChange={(e) => setFormData({ ...formData, isAnonymous: e.target.checked })}
                                        className="h-4 w-4"
                                    />
                                    <span className="text-sm">Posting sebagai Anonim</span>
                                </label>
                            </div>
                        </div>
                        <button
                            type="submit"
                            className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                        >
                            Simpan Jurnal
                        </button>
                    </form>
                </CardContent>
            </Card>

            <div className="space-y-4">
                <h2 className="text-xl font-bold">Riwayat Jurnal</h2>
                {loading ? (
                    <p>Loading...</p>
                ) : journals.length === 0 ? (
                    <p className="text-gray-500">Belum ada jurnal.</p>
                ) : (
                    journals.map((journal) => (
                        <Card key={journal.id}>
                            <CardHeader>
                                <div className="flex justify-between items-start">
                                    <div>
                                        <CardTitle>{journal.title}</CardTitle>
                                        <span className="text-xs text-gray-500">
                                            {new Date(journal.createdAt).toLocaleDateString()}
                                        </span>
                                    </div>
                                    <span className="px-2 py-1 rounded bg-gray-100 text-xs font-bold">
                                        {journal.mood}
                                    </span>
                                </div>
                            </CardHeader>
                            <CardContent>
                                <p className="whitespace-pre-wrap">{journal.content}</p>
                            </CardContent>
                        </Card>
                    ))
                )}
            </div>
        </div>
    );
}
