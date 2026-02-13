"use client";

import Image from "next/image";
import Link from "next/link";
import { useEffect, useState } from "react";
import {
    LineChart,
    Line,
    XAxis,
    YAxis,
    CartesianGrid,
    Tooltip,
    ResponsiveContainer,
} from "recharts";
import { Brain, Sparkles, TrendingUp } from "lucide-react";
import { getTeacherDashboardSummary } from "@/actions/teacher";

// Mock data for MVP - In real app, fetch from API
const moodData = [
    { name: "Mon", happy: 4, sad: 2, angry: 1 },
    { name: "Tue", happy: 3, sad: 3, angry: 0 },
    { name: "Wed", happy: 5, sad: 1, angry: 1 },
    { name: "Thu", happy: 4, sad: 2, angry: 2 },
    { name: "Fri", happy: 6, sad: 1, angry: 0 },
    { name: "Sat", happy: 5, sad: 0, angry: 0 },
    { name: "Sun", happy: 5, sad: 1, angry: 1 },
];

export default function TeacherDashboard() {
    // const { data: session } = useSession();
    const [mounted, setMounted] = useState(false);
    const [summary, setSummary] = useState<string>("");
    const [loadingSummary, setLoadingSummary] = useState(true);

    useEffect(() => {
        setMounted(true);

        async function fetchSummary() {
            try {
                const result = await getTeacherDashboardSummary();
                setSummary(result);
            } catch (error) {
                console.error("Failed to fetch summary", error);
                setSummary("Gagal memuat ringkasan AI.");
            } finally {
                setLoadingSummary(false);
            }
        }

        fetchSummary();
    }, []);

    if (!mounted) return null;

    return (
        <div className="min-h-screen bg-gray-50 pb-20">
            {/* Hero Section */}
            <div className="relative min-h-[60vh] flex items-center overflow-hidden">
                <div className="absolute inset-0 z-0">
                    <Image
                        src="/images/teacher-hero-v2.png"
                        alt="Teacher Dashboard"
                        fill
                        className="object-cover object-center"
                        priority
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/50 to-slate-900/20"></div>
                </div>

                <div className="relative z-10 w-full max-w-7xl mx-auto px-6 lg:px-8 py-16">
                    <div className="max-w-2xl">
                        <div className="inline-flex items-center gap-2 px-4 py-2 bg-teal-500/20 border-2 border-teal-400/40 rounded-full mb-6 backdrop-blur-md">
                            <span className="w-2 h-2 bg-teal-400 rounded-full animate-pulse"></span>
                            <span className="text-xs font-bold text-teal-300 uppercase tracking-wider">
                                Portal Guru BK
                            </span>
                        </div>

                        <h1 className="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                            Berdayakan Kelas Anda,
                            <br />
                            <span className="text-transparent bg-clip-text bg-gradient-to-r from-teal-300 via-teal-400 to-emerald-400">
                                Bentuk Pemimpin Masa Depan
                            </span>
                        </h1>

                        <p className="text-lg md:text-xl text-slate-100 mb-10 leading-relaxed font-normal">
                            Selamat datang di pusat kontrol kelas. Pantau kesejahteraan{" "}
                            <strong className="text-teal-300 font-semibold">32 siswa</strong>{" "}
                            dengan wawasan berbasis AI.
                        </p>

                        <div className="flex flex-col sm:flex-row gap-4 mb-12">
                            <Link
                                href="/teacher/journals"
                                className="group px-6 py-3 bg-teal-500 hover:bg-teal-400 text-white font-semibold text-base rounded-xl shadow-lg shadow-teal-500/30 transition-all duration-300 hover:-translate-y-0.5 flex items-center justify-center gap-2"
                            >
                                <span>Lihat Aktivitas Siswa</span>
                                <TrendingUp className="w-5 h-5 transition-transform group-hover:translate-x-1" />
                            </Link>
                        </div>

                        {/* Stats Cards */}
                        {/* Stats Cards */}
                        <div className="grid grid-cols-2 md:flex md:flex-row items-stretch gap-4">
                            <div className="backdrop-blur-md bg-white/10 px-6 py-5 rounded-3xl border-2 border-white/20 shadow-lg shadow-black/20 md:min-w-[160px] flex flex-col justify-center">
                                <p className="text-3xl md:text-4xl font-bold text-white mb-1">5</p>
                                <p className="text-xs md:text-sm text-slate-200 font-medium uppercase tracking-wide">Siswa Berisiko</p>
                            </div>
                            <div className="backdrop-blur-md bg-white/10 px-6 py-5 rounded-3xl border-2 border-white/20 shadow-lg shadow-black/20 md:min-w-[160px] flex flex-col justify-center">
                                <p className="text-3xl md:text-4xl font-bold text-white flex items-center gap-2 mb-1">
                                    <span className="text-2xl md:text-3xl">🙂</span>
                                    <span>Calm</span>
                                </p>
                                <p className="text-xs md:text-sm text-slate-200 font-medium uppercase tracking-wide">Mood Dominan</p>
                            </div>
                            <div className="col-span-2 md:col-span-1 backdrop-blur-md bg-white/10 px-6 py-5 rounded-3xl border-2 border-white/20 shadow-lg shadow-black/20 md:min-w-[160px] flex flex-col justify-center items-center">
                                <p className="text-3xl md:text-4xl font-bold text-white mb-1">32</p>
                                <p className="text-xs md:text-sm text-slate-200 font-medium uppercase tracking-wide">Total Siswa</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Wave Divider */}
            <div className="relative -mt-1">
                <svg viewBox="0 0 1440 120" className="w-full h-24" preserveAspectRatio="none" fill="none">
                    <path d="M0,64 C240,90 480,90 720,64 C960,38 1200,38 1440,64 L1440,120 L0,120 Z" fill="#F9FAFB" />
                </svg>
            </div>

            {/* Bento Box Layout */}
            <div className="relative z-20 max-w-7xl mx-auto px-6 lg:px-8 -mt-12 mb-12">
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {/* Critical Alerts */}
                    <div className="lg:col-span-2 bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 border border-slate-100">
                        <div className="flex items-center justify-between mb-6">
                            <div>
                                <h3 className="text-lg font-bold text-gray-900 flex items-center gap-2">
                                    <span className="flex h-3 w-3 relative">
                                        <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                        <span className="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                                    </span>
                                    Perlu Perhatian Segera
                                </h3>
                                <p className="text-sm text-gray-500 mt-1">Siswa dengan indikasi stres atau emosi negatif tinggi</p>
                            </div>
                            <Link href="/teacher/journals" className="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">Lihat Semua &rarr;</Link>
                        </div>
                        {/* Mock Data List */}
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {[1, 2].map((i) => (
                                <div key={i} className="flex items-start gap-3 p-4 rounded-2xl bg-red-50/50 border border-red-100 hover:shadow-md transition-all duration-300 cursor-pointer group">
                                    <div className="relative">
                                        <div className="w-10 h-10 rounded-full bg-gradient-to-br from-red-100 to-red-200 flex items-center justify-center text-red-600 font-bold text-sm">
                                            S{i}
                                        </div>
                                        <div className="absolute -bottom-1 -right-1 bg-white rounded-full p-0.5 shadow-sm">
                                            <span className="block w-3 h-3 bg-red-500 rounded-full border-2 border-white"></span>
                                        </div>
                                    </div>
                                    <div className="flex-1 min-w-0">
                                        <h4 className="text-sm font-bold text-gray-900 truncate group-hover:text-red-700 transition-colors">Siswa {i}</h4>
                                        <div className="flex items-center gap-2 mt-1">
                                            <span className="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-700">
                                                Stress
                                            </span>
                                            <span className="text-xs text-gray-400">2 jam lalu</span>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* AI Summary */}
                    <div className="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-3xl p-6 shadow-xl shadow-indigo-200 text-white relative overflow-hidden">
                        {/* Decor */}
                        <div className="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>

                        <h3 className="text-lg font-bold mb-4 flex items-center gap-2 relative z-10">
                            <Brain className="w-5 h-5" />
                            Ringkasan AI Minggu Ini
                        </h3>

                        <div className="prose prose-sm prose-invert relative z-10">
                            {loadingSummary ? (
                                <div className="flex items-center gap-2 animate-pulse text-indigo-200">
                                    <Sparkles className="w-4 h-4 animate-spin" />
                                    <span>Sedang menganalisis data mood siswa...</span>
                                </div>
                            ) : (
                                <p className="text-indigo-100 leading-relaxed text-sm">
                                    &quot;{summary}&quot;
                                </p>
                            )}
                        </div>

                        <div className="mt-6 pt-4 border-t border-white/20 relative z-10">
                            <div className="flex items-center justify-between text-xs text-indigo-200">
                                <span>Update: Hari ini</span>
                                <span className="flex items-center gap-1">
                                    Powered by Gemini <Sparkles className="w-3 h-3" />
                                </span>
                            </div>
                        </div>
                    </div>

                    {/* Charts Section */}
                    <div className="lg:col-span-3 bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 border border-slate-100">
                        <h3 className="text-lg font-bold text-gray-900 mb-6">Analisis Mood 7 Hari Terakhir</h3>
                        <div className="h-64 w-full">
                            <ResponsiveContainer width="100%" height="100%">
                                <LineChart data={moodData}>
                                    <CartesianGrid strokeDasharray="3 3" stroke="#f1f5f9" />
                                    <XAxis dataKey="name" stroke="#64748b" fontSize={12} tickLine={false} axisLine={false} />
                                    <YAxis stroke="#64748b" fontSize={12} tickLine={false} axisLine={false} />
                                    <Tooltip
                                        contentStyle={{ backgroundColor: '#fff', borderRadius: '12px', borderColor: '#e2e8f0', boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1)' }}
                                        itemStyle={{ fontSize: '12px', fontWeight: 500 }}
                                    />
                                    <Line type="monotone" dataKey="happy" stroke="#2dd4bf" strokeWidth={3} dot={{ r: 4, fill: '#2dd4bf', strokeWidth: 0 }} activeDot={{ r: 6 }} />
                                    <Line type="monotone" dataKey="sad" stroke="#fdba74" strokeWidth={3} dot={{ r: 4, fill: '#fdba74', strokeWidth: 0 }} />
                                    <Line type="monotone" dataKey="angry" stroke="#f87171" strokeWidth={3} dot={{ r: 4, fill: '#f87171', strokeWidth: 0 }} />
                                </LineChart>
                            </ResponsiveContainer>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    );
}
