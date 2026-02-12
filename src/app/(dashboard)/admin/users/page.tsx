import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import { db } from "@/lib/db";
import { Card, CardHeader, CardTitle, CardContent } from "@/components/ui/card";
import { UserPlus } from "lucide-react";
import CreateUserForm from "./CreateUserForm";

export default async function AdminUsersPage() {
    const session = await getServerSession(authOptions);
    if (!session) return null;

    const users = await db.user.findMany({
        orderBy: { createdAt: 'desc' }
    });

    return (
        <div className="space-y-8">
            <div className="flex justify-between items-center">
                <h1 className="text-2xl font-bold">User Management</h1>
            </div>

            <div className="grid md:grid-cols-3 gap-8">
                <div className="md:col-span-2 space-y-4">
                    <Card>
                        <CardHeader><CardTitle>All Users</CardTitle></CardHeader>
                        <CardContent>
                            <div className="bg-white rounded border overflow-hidden">
                                <table className="w-full text-sm text-left">
                                    <thead className="bg-gray-50 text-gray-700">
                                        <tr>
                                            <th className="p-3">Name</th>
                                            <th className="p-3">Email</th>
                                            <th className="p-3">Role</th>
                                            <th className="p-3">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {users.map(user => (
                                            <tr key={user.id} className="border-t hover:bg-gray-50">
                                                <td className="p-3">{user.name}</td>
                                                <td className="p-3">{user.email}</td>
                                                <td className="p-3">
                                                    <span className={`px-2 py-1 rounded text-xs font-bold ${user.role === 'ADMIN' ? 'bg-red-100 text-red-600' :
                                                            user.role === 'TEACHER' ? 'bg-purple-100 text-purple-600' :
                                                                'bg-blue-100 text-blue-600'
                                                        }`}>
                                                        {user.role}
                                                    </span>
                                                </td>
                                                <td className="p-3">
                                                    {/* Edit/Delete Placeholder */}
                                                    <button className="text-blue-600 hover:underline">Edit</button>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <div>
                    <Card>
                        <CardHeader><CardTitle>Add New User</CardTitle></CardHeader>
                        <CardContent>
                            <CreateUserForm />
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    );
}
