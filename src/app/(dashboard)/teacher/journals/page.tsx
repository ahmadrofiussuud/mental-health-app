"use client";

import { useEffect, useState } from "react";
import { formatDistanceToNow } from "date-fns";
import { id } from "date-fns/locale";
import { Search, Filter, BookOpen, X, Sparkles, Brain, AlertTriangle, CheckCircle } from "lucide-react";
import { analyzeJournalContent } from "@/actions/teacher";

// Mock data for MVP
const mockJournals = [
    {
        id: "1",
        studentName: "Siswa 1",
        avatar: "S1",
        mood: "Happy",
        title: "Hari yang menyenangkan",
        content: "Hari ini saya belajar banyak hal baru di sekolah. Teman-teman sangat membantu. Saya merasa sangat dihargai oleh guru matematika saya karena berhasil menjawab pertanyaan sulit.",
        createdAt: new Date(Date.now() - 1000 * 60 * 60 * 2), // 2 hours ago
    },
    {
        id: "2",
        studentName: "Siswa 2",
        avatar: "S2",
        mood: "Sad",
        title: "Sedikit lelah",
        content: "Tugas matematika hari ini sangat sulit. Saya merasa sedikit putus asa. Rasanya ingin menyerah saja karena tidak ada yang mengerti kesulitan saya di rumah.",
        createdAt: new Date(Date.now() - 1000 * 60 * 60 * 24), // 1 day ago
    },
    {
        id: "3",
        studentName: "Siswa 3",
        avatar: "S3",
        mood: "Anxious",
        title: "Ujian besok",
        content: "Saya sangat gugup menghadapi ujian biologi besok. Semoga bisa mengerjakannya. Tangan saya berkeringat dingin setiap kali memikirkannya.",
        createdAt: new Date(Date.now() - 1000 * 60 * 60 * 48), // 2 days ago
    },
];

type AIAnalysis = {
    emotion?: string;
    riskLevel?: string;
    summary?: string;
    suggestions?: string[];
};

export default function TeacherJournalsPage() {
    // const { data: session } = useSession();
    const [mounted, setMounted] = useState(false);
    const [selectedJournal, setSelectedJournal] = useState<typeof mockJournals[0] | null>(null);
    const [isAnalyzing, setIsAnalyzing] = useState(false);
    const [analysis, setAnalysis] = useState<AIAnalysis | null>(null);

    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        setMounted(true);
    }, []);

    const handleAnalyze = async () => {
        if (!selectedJournal) return;
        setIsAnalyzing(true);
        setAnalysis(null);
        setError(null);
        try {
            const result = await analyzeJournalContent(selectedJournal.content);
            if (result && !result.error) {
                setAnalysis({
                    emotion: result.emotion,
                    riskLevel: result.riskLevel,
                    summary: result.summary,
                    suggestions: result.suggestions || []
                });
            } else if (result && result.error) {
                setError(result.message);
            } else {
                setError("Gagal mendapatkan respon dari AI.");
            }
        } catch (error) {
            console.error(error);
            setError("Terjadi kesalahan sistem saat menghubungi AI.");
        } finally {
            setIsAnalyzing(false);
        }
    };

    const closeModal = () => {
        setSelectedJournal(null);
        setAnalysis(null);
        setError(null);
        setIsAnalyzing(false);
    }

    if (!mounted) return null;

    return (
        <div className="min-h-screen bg-gray-50 pb-20 relative">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900">Log Jurnal Siswa</h1>
                        <p className="text-sm text-gray-500 mt-1">Pantau aktivitas dan perasaan siswa melalui jurnal harian mereka.</p>
                    </div>

                    <div className="flex gap-2">
                        <div className="relative">
                            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                            <input
                                type="text"
                                placeholder="Cari siswa..."
                                className="pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 w-full md:w-64"
                            />
                        </div>
                        <button className="p-2 border border-gray-200 rounded-lg hover:bg-gray-50 text-gray-600">
                            <Filter className="w-5 h-5" />
                        </button>
                    </div>
                </div>

                <div className="grid gap-6">
                    {mockJournals.map((journal) => (
                        <div key={journal.id} className="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                            <div className="flex items-start gap-4">
                                <div className="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-bold shrink-0">
                                    {journal.avatar}
                                </div>
                                <div className="flex-1 min-w-0">
                                    <div className="flex items-center justify-between mb-2">
                                        <h3 className="text-base font-semibold text-gray-900">{journal.studentName}</h3>
                                        <span className="text-xs text-gray-400 whitespace-nowrap">
                                            {formatDistanceToNow(journal.createdAt, { addSuffix: true, locale: id })}
                                        </span>
                                    </div>

                                    <div className="flex items-center gap-2 mb-3">
                                        <span className={`inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                      ${journal.mood === 'Happy' ? 'bg-green-100 text-green-700' :
                                                journal.mood === 'Sad' ? 'bg-blue-100 text-blue-700' :
                                                    journal.mood === 'Anxious' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700'}`}>
                                            {journal.mood}
                                        </span>
                                        <span className="text-gray-300">•</span>
                                        <span className="text-sm font-medium text-gray-700">{journal.title}</span>
                                    </div>

                                    <p className="text-gray-600 text-sm leading-relaxed mb-4 line-clamp-2">
                                        {journal.content}
                                    </p>

                                    <div className="flex items-center gap-4 pt-4 border-t border-gray-50">
                                        <button
                                            onClick={() => setSelectedJournal(journal)}
                                            className="text-sm text-teal-600 font-medium hover:text-teal-700 flex items-center gap-1 transition-colors"
                                        >
                                            <BookOpen className="w-4 h-4" /> Baca Selengkapnya
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            </div>

            {/* Modal / Dialog */}
            {selectedJournal && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
                    <div
                        className="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity"
                        onClick={closeModal}
                    ></div>

                    <div className="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto flex flex-col animate-in fade-in zoom-in-95 duration-200">
                        <button
                            onClick={closeModal}
                            className="absolute top-4 right-4 p-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100 transition-colors z-10"
                        >
                            <X className="w-5 h-5" />
                        </button>

                        <div className="p-6 sm:p-8">
                            <div className="flex items-center gap-4 mb-6">
                                <div className="w-12 h-12 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-bold text-lg">
                                    {selectedJournal.avatar}
                                </div>
                                <div>
                                    <h2 className="text-xl font-bold text-gray-900">{selectedJournal.studentName}</h2>
                                    <p className="text-sm text-gray-500">
                                        {formatDistanceToNow(selectedJournal.createdAt, { addSuffix: true, locale: id })} • {selectedJournal.mood}
                                    </p>
                                </div>
                            </div>

                            <div className="mb-8">
                                <h3 className="text-lg font-semibold text-gray-900 mb-2">{selectedJournal.title}</h3>
                                <div className="prose prose-slate max-w-none text-gray-600 leading-relaxed bg-gray-50 p-6 rounded-2xl border border-gray-100">
                                    <p>{selectedJournal.content}</p>
                                </div>
                            </div>

                            {/* AI Analysis Section */}
                            <div className="border-t border-gray-100 pt-6">
                                <div className="flex items-center justify-between mb-4">
                                    <h3 className="text-lg font-bold text-gray-900 flex items-center gap-2">
                                        <Sparkles className="w-5 h-5 text-indigo-500" />
                                        Analisis AI Assistant
                                    </h3>
                                    {!analysis && !isAnalyzing && (
                                        <button
                                            onClick={handleAnalyze}
                                            className="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center gap-2 shadow-lg shadow-indigo-200"
                                        >
                                            <Brain className="w-4 h-4" />
                                            Analisis Sekarang
                                        </button>
                                    )}
                                </div>

                                {isAnalyzing && (
                                    <div className="flex flex-col items-center justify-center py-8 text-indigo-600 bg-indigo-50/50 rounded-xl border border-indigo-100/50 animate-pulse">
                                        <Brain className="w-8 h-8 mb-3 animate-bounce" />
                                        <p className="text-sm font-medium">Sedang menganalisis emosi dan konten...</p>
                                    </div>
                                )}

                                {error && (
                                    <div className="bg-red-50 text-red-600 p-4 rounded-xl border border-red-100 flex items-center gap-3 animate-in fade-in slide-in-from-bottom-2">
                                        <AlertTriangle className="w-5 h-5 shrink-0" />
                                        <p className="text-sm font-medium">{error}</p>
                                    </div>
                                )}

                                {analysis && (
                                    <div className="bg-indigo-50 rounded-2xl p-6 border border-indigo-100 animate-in fade-in slide-in-from-bottom-4 duration-500">
                                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                            <div className="bg-white p-4 rounded-xl border border-indigo-100 shadow-sm">
                                                <p className="text-xs font-bold text-indigo-400 uppercase tracking-wider mb-1">Emosi Dominan</p>
                                                <p className="text-lg font-bold text-indigo-900">{analysis.emotion}</p>
                                            </div>
                                            <div className="bg-white p-4 rounded-xl border border-indigo-100 shadow-sm">
                                                <p className="text-xs font-bold text-indigo-400 uppercase tracking-wider mb-1">Tingkat Risiko</p>
                                                <div className="flex items-center gap-2">
                                                    {analysis.riskLevel === 'High' ? (
                                                        <AlertTriangle className="w-5 h-5 text-red-500" />
                                                    ) : analysis.riskLevel === 'Medium' ? (
                                                        <AlertTriangle className="w-5 h-5 text-yellow-500" />
                                                    ) : (
                                                        <CheckCircle className="w-5 h-5 text-green-500" />
                                                    )}
                                                    <p className={`text-lg font-bold ${analysis.riskLevel === 'High' ? 'text-red-600' :
                                                        analysis.riskLevel === 'Medium' ? 'text-yellow-600' : 'text-green-600'
                                                        }`}>
                                                        {analysis.riskLevel}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div className="mb-4">
                                            <p className="text-sm font-bold text-indigo-900 mb-2">Ringkasan Psikologis</p>
                                            <p className="text-sm text-indigo-800 leading-relaxed bg-white/50 p-3 rounded-lg border border-indigo-100/50">
                                                {analysis.summary}
                                            </p>
                                        </div>

                                        {analysis.suggestions && analysis.suggestions.length > 0 && (
                                            <div>
                                                <p className="text-sm font-bold text-indigo-900 mb-2">Saran Pendekatan</p>
                                                <ul className="space-y-2">
                                                    {analysis.suggestions.map((suggestion, idx) => (
                                                        <li key={idx} className="flex items-start gap-2 text-sm text-indigo-800">
                                                            <span className="mt-1.5 w-1.5 h-1.5 bg-indigo-400 rounded-full shrink-0"></span>
                                                            <span className="bg-white/50 px-2 py-1 rounded-md border border-indigo-100/50 w-full">{suggestion}</span>
                                                        </li>
                                                    ))}
                                                </ul>
                                            </div>
                                        )}
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}

