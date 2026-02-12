"use client";

import { signOut } from "next-auth/react";
import { LogOut } from "lucide-react";

export default function LogoutButton() {
    return (
        <button
            onClick={() => signOut({ callbackUrl: "/login" })}
            className="flex w-full items-center gap-3 rounded-lg px-4 py-2 text-red-600 hover:bg-red-50"
        >
            <LogOut size={20} /> Logout
        </button>
    );
}
