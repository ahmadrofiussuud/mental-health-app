"use client";

import { useSession, signOut } from "next-auth/react";
import Image from "next/image";
import { User, Mail, Shield, LogOut, Loader2 } from "lucide-react";

export default function ProfilePage() {
    const { data: session, status } = useSession();

    if (status === "loading") {
        return (
            <div className="min-h-screen flex items-center justify-center bg-gray-50">
                <Loader2 className="w-8 h-8 animate-spin text-teal-600" />
            </div>
        );
    }

    if (!session) {
        return (
            <div className="min-h-screen flex flex-col items-center justify-center bg-gray-50 text-gray-500">
                <p>Anda belum login.</p>
            </div>
        );
    }

    const user = session.user;
    const roleColors = user.role === "TEACHER" || user.role === "teacher"
        ? "bg-indigo-100 text-indigo-700 border-indigo-200"
        : "bg-teal-100 text-teal-700 border-teal-200";

    return (
        <div className="min-h-screen bg-gray-50/50 py-12 px-4 sm:px-6 lg:px-8">
            <div className="max-w-3xl mx-auto space-y-8">
                {/* Header */}
                <div className="text-center">
                    <h1 className="text-3xl font-black text-slate-900 tracking-tight">Profil Saya</h1>
                    <p className="text-slate-500 mt-2">Kelola informasi akun dan preferensi Anda.</p>
                </div>

                {/* Profile Card */}
                <div className="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                    {/* Banner / Cover (Optional decorative) */}
                    <div className="h-32 bg-gradient-to-r from-teal-500 to-emerald-500 relative">
                        <div className="absolute -bottom-12 left-1/2 transform -translate-x-1/2">
                            <div className="w-24 h-24 rounded-full bg-white p-1.5 shadow-lg">
                                <div className="w-full h-full rounded-full bg-slate-100 flex items-center justify-center text-3xl font-bold text-slate-400 overflow-hidden relative">
                                    {user.image ? (
                                        <Image
                                            src={user.image}
                                            alt={user.name || "User"}
                                            fill
                                            className="object-cover"
                                        />
                                    ) : (
                                        <span>{user.name?.[0]?.toUpperCase() || "U"}</span>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="pt-16 pb-8 px-8 text-center">
                        <h2 className="text-2xl font-bold text-slate-800">{user.name}</h2>
                        <div className="flex justify-center mt-2">
                            <span className={`px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border ${roleColors}`}>
                                {user.role}
                            </span>
                        </div>
                    </div>

                    <div className="border-t border-slate-100">
                        <div className="grid grid-cols-1 divide-y divide-slate-100">
                            {/* Email Section */}
                            <div className="p-6 flex items-center gap-4 hover:bg-slate-50 transition-colors">
                                <div className="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                                    <Mail className="w-5 h-5" />
                                </div>
                                <div className="flex-1">
                                    <p className="text-sm font-medium text-slate-500">Email Address</p>
                                    <p className="text-base font-semibold text-slate-800">{user.email}</p>
                                </div>
                            </div>

                            {/* ID/Username Section (if applicable, using ID for now) */}
                            <div className="p-6 flex items-center gap-4 hover:bg-slate-50 transition-colors">
                                <div className="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center text-purple-600 shrink-0">
                                    <User className="w-5 h-5" />
                                </div>
                                <div className="flex-1">
                                    <p className="text-sm font-medium text-slate-500">Account ID</p>
                                    <p className="text-base font-semibold text-slate-800 font-mono text-xs">{user.id}</p>
                                </div>
                            </div>

                            {/* Role Section Description */}
                            <div className="p-6 flex items-center gap-4 hover:bg-slate-50 transition-colors">
                                <div className="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                                    <Shield className="w-5 h-5" />
                                </div>
                                <div className="flex-1">
                                    <p className="text-sm font-medium text-slate-500">Hak Akses</p>
                                    <p className="text-base font-semibold text-slate-800">
                                        {user.role === "TEACHER" ? "Akses Penuh Guru (Dashboard & Jurnal)" : "Akses Siswa (Jurnal & Mood)"}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="p-8 bg-slate-50/50 border-t border-slate-100 flex flex-col sm:flex-row gap-4 justify-center">
                        <button
                            onClick={() => signOut({ callbackUrl: "/login" })}
                            className="flex items-center justify-center gap-2 px-6 py-3 bg-white border border-red-200 text-red-600 font-bold rounded-xl hover:bg-red-50 hover:border-red-300 shadow-sm transition-all w-full sm:w-auto"
                        >
                            <LogOut className="w-4 h-4" />
                            Sign Out
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
}
