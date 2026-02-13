"use client";

import { useState } from "react";
import { signIn } from "next-auth/react";
import { useRouter } from "next/navigation";
import Image from "next/image";
import { Eye, EyeOff, GraduationCap, BookOpen, Loader2 } from "lucide-react";

export default function LoginPage() {
    const router = useRouter();
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [error, setError] = useState("");
    const [showPassword, setShowPassword] = useState(false);
    const [isLoading, setIsLoading] = useState(false);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setIsLoading(true);
        setError("");

        const res = await signIn("credentials", {
            email,
            password,
            redirect: false,
        });

        if (res?.error) {
            setError("Email atau password salah. Coba lagi.");
            setIsLoading(false);
        } else {
            const response = await fetch("/api/auth/session");
            const session = await response.json();

            if (session?.user?.role === "TEACHER" || session?.user?.role === "teacher") {
                router.push("/teacher/dashboard");
            } else if (session?.user?.role === "ADMIN" || session?.user?.role === "admin") {
                router.push("/admin/dashboard");
            } else {
                router.push("/student/dashboard");
            }
            router.refresh();
        }
    };

    const fillCredentials = (emailVal: string, passwordVal: string) => {
        setEmail(emailVal);
        setPassword(passwordVal);
        setError("");
    };

    return (
        <div className="min-h-screen flex font-sans">
            {/* Left Panel - Decorative */}
            <div className="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-teal-500 via-teal-600 to-indigo-700 overflow-hidden">
                {/* Decorative circles */}
                <div className="absolute -top-20 -left-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
                <div className="absolute top-1/2 -right-32 w-96 h-96 bg-indigo-400/20 rounded-full blur-3xl"></div>
                <div className="absolute -bottom-20 left-1/4 w-72 h-72 bg-teal-300/20 rounded-full blur-3xl"></div>

                <div className="relative z-10 flex flex-col items-center justify-center w-full px-16 text-white">
                    <div className="w-32 h-32 mb-8 relative">
                        <Image
                            src="/images/serenity-hub-logo.png"
                            alt="SerenityHub Logo"
                            fill
                            className="object-contain drop-shadow-2xl brightness-0 invert"
                        />
                    </div>
                    <h1 className="text-4xl font-bold mb-4 text-center">SerenityHub</h1>
                    <p className="text-lg text-white/80 text-center max-w-md leading-relaxed">
                        Platform kesehatan mental siswa berbasis AI. Ruang aman untuk pertumbuhan emosi dan konseling digital.
                    </p>

                    {/* Feature highlights */}
                    <div className="mt-12 space-y-4 w-full max-w-sm">
                        <div className="flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3">
                            <div className="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                                <span className="text-xl">🧠</span>
                            </div>
                            <div>
                                <p className="font-semibold text-sm">Analisis AI</p>
                                <p className="text-xs text-white/70">Powered by Google Gemini</p>
                            </div>
                        </div>
                        <div className="flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3">
                            <div className="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                                <span className="text-xl">📔</span>
                            </div>
                            <div>
                                <p className="font-semibold text-sm">Jurnal Harian</p>
                                <p className="text-xs text-white/70">Catat dan pantau suasana hati</p>
                            </div>
                        </div>
                        <div className="flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3">
                            <div className="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                                <span className="text-xl">🤝</span>
                            </div>
                            <div>
                                <p className="font-semibold text-sm">Koneksi Guru BK</p>
                                <p className="text-xs text-white/70">Monitoring real-time oleh guru</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Right Panel - Login Form */}
            <div className="flex-1 flex flex-col items-center justify-center px-6 py-12 bg-gray-50">
                {/* Mobile Logo */}
                <div className="lg:hidden flex flex-col items-center mb-8">
                    <div className="w-20 h-20 relative mb-3">
                        <Image
                            src="/images/serenity-hub-logo.png"
                            alt="SerenityHub Logo"
                            fill
                            className="object-contain"
                        />
                    </div>
                    <h1 className="text-2xl font-bold bg-gradient-to-r from-teal-600 to-indigo-600 bg-clip-text text-transparent">
                        SerenityHub
                    </h1>
                </div>

                <div className="w-full max-w-md">
                    <div className="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 p-8">
                        <div className="text-center mb-8">
                            <h2 className="text-2xl font-bold text-gray-800">Selamat Datang 👋</h2>
                            <p className="text-gray-500 mt-2 text-sm">Masuk ke akun Anda untuk melanjutkan</p>
                        </div>

                        {error && (
                            <div className="mb-6 p-3 rounded-xl bg-red-50 border border-red-200 text-red-600 text-sm flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" className="w-4 h-4 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clipRule="evenodd" />
                                </svg>
                                {error}
                            </div>
                        )}

                        <form onSubmit={handleSubmit} className="space-y-5">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                                <input
                                    type="email"
                                    value={email}
                                    onChange={(e) => setEmail(e.target.value)}
                                    placeholder="nama@email.com"
                                    className="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-teal-400 focus:ring-2 focus:ring-teal-100 outline-none transition-all text-sm"
                                    required
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                                <div className="relative">
                                    <input
                                        type={showPassword ? "text" : "password"}
                                        value={password}
                                        onChange={(e) => setPassword(e.target.value)}
                                        placeholder="••••••••"
                                        className="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-teal-400 focus:ring-2 focus:ring-teal-100 outline-none transition-all text-sm pr-12"
                                        required
                                    />
                                    <button
                                        type="button"
                                        onClick={() => setShowPassword(!showPassword)}
                                        className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
                                    >
                                        {showPassword ? <EyeOff className="w-5 h-5" /> : <Eye className="w-5 h-5" />}
                                    </button>
                                </div>
                            </div>
                            <button
                                type="submit"
                                disabled={isLoading}
                                className="w-full py-3 rounded-xl bg-gradient-to-r from-teal-500 to-indigo-600 text-white font-semibold shadow-lg shadow-teal-500/30 hover:shadow-xl hover:shadow-teal-500/40 hover:-translate-y-0.5 transition-all duration-300 disabled:opacity-60 disabled:cursor-not-allowed disabled:hover:translate-y-0 flex items-center justify-center gap-2"
                            >
                                {isLoading ? (
                                    <>
                                        <Loader2 className="w-5 h-5 animate-spin" />
                                        Memproses...
                                    </>
                                ) : (
                                    "Masuk"
                                )}
                            </button>
                        </form>
                    </div>

                    {/* Demo Credentials Hint */}
                    <div className="mt-6 bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 p-5">
                        <p className="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 text-center">
                            🔑 Demo Akses Cepat
                        </p>
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <button
                                onClick={() => fillCredentials("student@example.com", "password")}
                                className="group flex items-center gap-3 p-3 rounded-xl border-2 border-dashed border-teal-200 hover:border-teal-400 hover:bg-teal-50/50 transition-all duration-200"
                            >
                                <div className="w-10 h-10 rounded-lg bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center text-white shadow-sm group-hover:scale-110 transition-transform">
                                    <GraduationCap className="w-5 h-5" />
                                </div>
                                <div className="text-left">
                                    <p className="text-sm font-semibold text-gray-700">Siswa</p>
                                    <p className="text-[11px] text-gray-400">student@example.com</p>
                                </div>
                            </button>
                            <button
                                onClick={() => fillCredentials("teacher@example.com", "password")}
                                className="group flex items-center gap-3 p-3 rounded-xl border-2 border-dashed border-indigo-200 hover:border-indigo-400 hover:bg-indigo-50/50 transition-all duration-200"
                            >
                                <div className="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white shadow-sm group-hover:scale-110 transition-transform">
                                    <BookOpen className="w-5 h-5" />
                                </div>
                                <div className="text-left">
                                    <p className="text-sm font-semibold text-gray-700">Guru BK</p>
                                    <p className="text-[11px] text-gray-400">teacher@example.com</p>
                                </div>
                            </button>
                        </div>
                        <p className="text-[10px] text-gray-300 text-center mt-3">Password: <span className="font-mono">password</span></p>
                    </div>

                    <p className="text-center text-xs text-gray-400 mt-6">
                        © 2026 SerenityHub. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    );
}
