"use client";

import { useState, useEffect } from "react";
import { useRouter } from "next/navigation";
import { formatDistanceToNow } from "date-fns";
import { enUS } from "date-fns/locale";
import {
    Book,
    Send,
    Loader2,
    Lock,
    PenLine,
    Calendar,
    Sparkles
} from "lucide-react";

interface Journal {
    id: string;
    title: string;
    content: string;
    mood: "HAPPY" | "CALM" | "NEUTRAL" | "SAD" | "ANGRY";
    createdAt: string;
    isAnonymous: boolean;
}

const moodOptions = [
    { value: "HAPPY", label: "Happy", emoji: "😄", color: "bg-green-100 text-green-600 border-green-200 hover:bg-green-200" },
    { value: "CALM", label: "Calm", emoji: "😌", color: "bg-teal-100 text-teal-600 border-teal-200 hover:bg-teal-200" },
    { value: "NEUTRAL", label: "Neutral", emoji: "😐", color: "bg-gray-100 text-gray-600 border-gray-200 hover:bg-gray-200" },
    { value: "SAD", label: "Sad", emoji: "😢", color: "bg-blue-100 text-blue-600 border-blue-200 hover:bg-blue-200" },
    { value: "ANGRY", label: "Angry", emoji: "😠", color: "bg-red-100 text-red-600 border-red-200 hover:bg-red-200" },
];

export default function StudentJournalsPage() {
    const router = useRouter();
    const [journals, setJournals] = useState<Journal[]>([]);
    const [loading, setLoading] = useState(true);
    const [submitting, setSubmitting] = useState(false);
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
            })
            .catch(() => setLoading(false));
    }, []);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setSubmitting(true);
        try {
            await fetch("/api/journals", {
                method: "POST",
                body: JSON.stringify(formData),
                headers: { "Content-Type": "application/json" },
            });
            setFormData({ title: "", content: "", mood: "NEUTRAL", isAnonymous: false });
            router.refresh();

            // Re-fetch to update list
            const res = await fetch("/api/journals");
            const newJournals = await res.json();
            setJournals(newJournals);
        } catch (error) {
            console.error("Failed to submit journal", error);
        } finally {
            setSubmitting(false);
        }
    };

    const getMoodStyle = (mood: string) => {
        const option = moodOptions.find(o => o.value === mood);
        return option ? option.color : "bg-gray-100 text-gray-600";
    };

    const getMoodEmoji = (mood: string) => {
        const option = moodOptions.find(o => o.value === mood);
        return option ? option.emoji : "😐";
    };

    return (
        <div className="min-h-screen bg-gray-50/50 pb-20">
            {/* Hero Header */}
            <div className="bg-white border-b border-gray-100 pb-12 pt-10 px-4 sm:px-6 lg:px-8">
                <div className="max-w-4xl mx-auto text-center">
                    <div className="flex justify-center mb-4">
                        <div className="p-3 bg-indigo-50 rounded-2xl">
                            <Book className="w-8 h-8 text-indigo-600" />
                        </div>
                    </div>
                    <h1 className="text-3xl font-black text-slate-900 mb-3 tracking-tight">
                        Stories & Reflections
                    </h1>
                    <p className="text-lg text-slate-500 max-w-2xl mx-auto leading-relaxed">
                        Write what you feel today. Your journal is a safe place to express yourself.
                    </p>
                </div>
            </div>

            <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8">
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {/* Left Column: Input Form */}
                    <div className="lg:col-span-1">
                        <div className="bg-white rounded-[1.5rem] shadow-xl shadow-indigo-100/50 border border-slate-100 p-6 sticky top-24">
                            <div className="flex items-center gap-2 mb-6">
                                <PenLine className="w-5 h-5 text-indigo-500" />
                                <h2 className="font-bold text-slate-800">Write Journal</h2>
                            </div>

                            <form onSubmit={handleSubmit} className="space-y-5">
                                <div>
                                    <label className="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                        Your Mood
                                    </label>
                                    <div className="grid grid-cols-5 gap-2">
                                        {moodOptions.map((option) => (
                                            <button
                                                key={option.value}
                                                type="button"
                                                onClick={() => setFormData({ ...formData, mood: option.value })}
                                                className={`aspect-square rounded-xl flex items-center justify-center text-xl transition-all duration-200 ${formData.mood === option.value
                                                    ? `${option.color} ring-2 ring-offset-2 ring-indigo-500 shadow-md scale-110`
                                                    : "bg-slate-50 text-slate-400 hover:bg-slate-100 grayscale hover:grayscale-0"
                                                    }`}
                                                title={option.label}
                                            >
                                                {option.emoji}
                                            </button>
                                        ))}
                                    </div>
                                    <p className="text-center text-xs font-medium text-slate-500 mt-2">
                                        {moodOptions.find(o => o.value === formData.mood)?.label}
                                    </p>
                                </div>

                                <div>
                                    <label className="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                        Title
                                    </label>
                                    <input
                                        type="text"
                                        className="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400"
                                        placeholder="e.g. A tiring day..."
                                        value={formData.title}
                                        onChange={(e) => setFormData({ ...formData, title: e.target.value })}
                                        required
                                    />
                                </div>

                                <div>
                                    <label className="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                        Your Story
                                    </label>
                                    <textarea
                                        className="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400 min-h-[120px] resize-none"
                                        placeholder="Pour out your feelings here..."
                                        value={formData.content}
                                        onChange={(e) => setFormData({ ...formData, content: e.target.value })}
                                        required
                                        style={{ lineHeight: "1.6" }}
                                    />
                                </div>

                                <div className="flex items-center justify-between pt-2">
                                    <label className="flex items-center gap-2 cursor-pointer group">
                                        <div className={`w-5 h-5 rounded border flex items-center justify-center transition-colors ${formData.isAnonymous ? "bg-indigo-600 border-indigo-600" : "border-slate-300 bg-white"}`}>
                                            {formData.isAnonymous && <Lock className="w-3 h-3 text-white" />}
                                        </div>
                                        <input
                                            type="checkbox"
                                            checked={formData.isAnonymous}
                                            onChange={(e) => setFormData({ ...formData, isAnonymous: e.target.checked })}
                                            className="hidden"
                                        />
                                        <span className={`text-xs font-medium transition-colors ${formData.isAnonymous ? "text-indigo-600" : "text-slate-500 group-hover:text-slate-700"}`}>
                                            Post Anonymously
                                        </span>
                                    </label>
                                </div>

                                <button
                                    type="submit"
                                    disabled={submitting}
                                    className="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-indigo-200 transition-all transform active:scale-95 disabled:opacity-70 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                                >
                                    {submitting ? (
                                        <>
                                            <Loader2 className="w-4 h-4 animate-spin" />
                                            Saving...
                                        </>
                                    ) : (
                                        <>
                                            <Send className="w-4 h-4" />
                                            Simpan Jurnal
                                        </>
                                    )}
                                </button>
                            </form>
                        </div>
                    </div>

                    {/* Right Column: Journal List */}
                    <div className="lg:col-span-2 space-y-6">
                        <div className="flex items-center justify-between mb-4">
                            <h2 className="font-bold text-slate-800 flex items-center gap-2">
                                <Calendar className="w-5 h-5 text-indigo-500" />
                                Journal History
                            </h2>
                            <span className="text-xs font-medium text-slate-500 bg-white px-3 py-1 rounded-full border border-slate-100 shadow-sm">
                                {journals.length} Entries
                            </span>
                        </div>

                        {loading ? (
                            <div className="flex flex-col items-center justify-center py-12 text-slate-400">
                                <Loader2 className="w-8 h-8 animate-spin mb-3" />
                                <p className="text-sm">Loading journals...</p>
                            </div>
                        ) : journals.length === 0 ? (
                            <div className="bg-white rounded-3xl p-12 text-center border border-dashed border-slate-200">
                                <div className="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                                    📝
                                </div>
                                <h3 className="text-lg font-bold text-slate-900 mb-2">No journals yet</h3>
                                <p className="text-slate-500 text-sm max-w-xs mx-auto">
                                    Start writing your experiences and feelings in the form on the left.
                                </p>
                            </div>
                        ) : (
                            <div className="space-y-5">
                                {journals.map((journal) => (
                                    <div
                                        key={journal.id}
                                        className="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all group relative overflow-hidden"
                                    >
                                        <div className={`absolute top-0 left-0 w-1 h-full ${getMoodStyle(journal.mood).split(" ")[0]}`}></div>

                                        <div className="flex justify-between items-start mb-4 pl-4">
                                            <div className="flex items-center gap-3">
                                                <div className={`w-10 h-10 rounded-full flex items-center justify-center text-xl shrink-0 ${getMoodStyle(journal.mood)}`}>
                                                    {getMoodEmoji(journal.mood)}
                                                </div>
                                                <div>
                                                    <h3 className="font-bold text-slate-800 text-lg leading-tight">
                                                        {journal.title}
                                                    </h3>
                                                    <p className="text-xs text-slate-400 mt-1 flex items-center gap-1">
                                                        {formatDistanceToNow(new Date(journal.createdAt), { addSuffix: true, locale: enUS })}
                                                        {journal.isAnonymous && (
                                                            <span className="flex items-center gap-1 ml-2 text-indigo-400 bg-indigo-50 px-1.5 py-0.5 rounded-full">
                                                                <Lock className="w-3 h-3" /> Anonymous
                                                            </span>
                                                        )}
                                                    </p>
                                                </div>
                                            </div>

                                            <button className="text-slate-300 hover:text-indigo-600 transition-colors">
                                                <Sparkles className="w-4 h-4" />
                                            </button>
                                        </div>

                                        <div className="pl-4 ml-10">
                                            <p className="text-slate-600 text-sm leading-relaxed whitespace-pre-wrap">
                                                {journal.content}
                                            </p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
}
