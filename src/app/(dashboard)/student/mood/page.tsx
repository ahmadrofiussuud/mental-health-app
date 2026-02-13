"use client";

import { useState, useEffect } from "react";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { useRouter } from "next/navigation";

interface MoodLog {
    id: string;
    mood: string;
    createdAt: string;
}

export default function MoodPage() {
    const router = useRouter();
    const [moods, setMoods] = useState<MoodLog[]>([]);

    useEffect(() => {
        fetch("/api/mood").then(res => res.json()).then(setMoods);
    }, []);

    const handleMoodSelect = async (mood: string) => {
        await fetch("/api/mood", {
            method: "POST",
            body: JSON.stringify({ mood }),
            headers: { "Content-Type": "application/json" }
        });
        const res = await fetch("/api/mood");
        setMoods(await res.json());
        router.refresh();
    };

    const moodOptions = [
        { value: "HAPPY", label: "Happy 😄", color: "bg-yellow-100 border-yellow-300" },
        { value: "CALM", label: "Calm 😌", color: "bg-blue-100 border-blue-300" },
        { value: "NEUTRAL", label: "Neutral 😐", color: "bg-gray-100 border-gray-300" },
        { value: "SAD", label: "Sad 😢", color: "bg-indigo-100 border-indigo-300" },
        { value: "ANGRY", label: "Angry 😠", color: "bg-red-100 border-red-300" },
    ];

    return (
        <div className="max-w-2xl mx-auto space-y-8">
            <Card>
                <CardHeader>
                    <CardTitle className="text-center">How are you feeling right now?</CardTitle>
                </CardHeader>
                <CardContent>
                    <div className="grid grid-cols-2 md:grid-cols-5 gap-4">
                        {moodOptions.map((option) => (
                            <button
                                key={option.value}
                                onClick={() => handleMoodSelect(option.value)}
                                className={`p-4 rounded-xl border-2 hover:scale-105 transition-transform ${option.color}`}
                            >
                                <div className="text-2xl mb-2">{option.label.split(" ")[1]}</div>
                                <div className="font-medium text-sm">{option.label.split(" ")[0]}</div>
                            </button>
                        ))}
                    </div>
                </CardContent>
            </Card>

            <div className="space-y-4">
                <h3 className="text-lg font-bold">Mood History</h3>
                <div className="space-y-2">
                    {moods.map((log) => (
                        <div key={log.id} className="flex justify-between items-center p-3 bg-white rounded shadow-sm border">
                            <span>{new Date(log.createdAt).toLocaleString()}</span>
                            <span className="font-bold">{log.mood}</span>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
}
