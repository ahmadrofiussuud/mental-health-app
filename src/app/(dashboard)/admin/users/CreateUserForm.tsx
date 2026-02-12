"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";

export default function CreateUserForm() {
    const router = useRouter();
    const [loading, setLoading] = useState(false);
    const [formData, setFormData] = useState({
        name: "",
        email: "",
        password: "",
        role: "STUDENT"
    });

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setLoading(true);

        try {
            // Ideally create a specific API route for admin user creation
            // For MVP using a simplifed approach or reusing an existing pattern
            // HERE: Assuming we implement /api/admin/users
            const res = await fetch("/api/admin/users", {
                method: "POST",
                body: JSON.stringify(formData),
                headers: { "Content-Type": "application/json" }
            });

            if (!res.ok) throw new Error("Failed");

            setFormData({ name: "", email: "", password: "", role: "STUDENT" });
            router.refresh();
            alert("User created!");
        } catch (e) {
            alert("Error creating user");
        } finally {
            setLoading(false);
        }
    };

    return (
        <form onSubmit={handleSubmit} className="space-y-3">
            <div>
                <label className="block text-xs font-medium text-gray-700">Name</label>
                <input
                    type="text" required
                    className="w-full mt-1 p-2 border rounded text-sm"
                    value={formData.name}
                    onChange={e => setFormData({ ...formData, name: e.target.value })}
                />
            </div>
            <div>
                <label className="block text-xs font-medium text-gray-700">Email</label>
                <input
                    type="email" required
                    className="w-full mt-1 p-2 border rounded text-sm"
                    value={formData.email}
                    onChange={e => setFormData({ ...formData, email: e.target.value })}
                />
            </div>
            <div>
                <label className="block text-xs font-medium text-gray-700">Password</label>
                <input
                    type="password" required
                    className="w-full mt-1 p-2 border rounded text-sm"
                    value={formData.password}
                    onChange={e => setFormData({ ...formData, password: e.target.value })}
                />
            </div>
            <div>
                <label className="block text-xs font-medium text-gray-700">Role</label>
                <select
                    className="w-full mt-1 p-2 border rounded text-sm"
                    value={formData.role}
                    onChange={e => setFormData({ ...formData, role: e.target.value })}
                >
                    <option value="STUDENT">Student</option>
                    <option value="TEACHER">Teacher</option>
                    <option value="ADMIN">Admin</option>
                </select>
            </div>
            <button
                type="submit"
                disabled={loading}
                className="w-full bg-blue-600 text-white py-2 rounded text-sm hover:bg-blue-700 disabled:opacity-50"
            >
                {loading ? "Creating..." : "Create User"}
            </button>
        </form>
    )
}
