"use client";

import Link from "next/link";
import Image from "next/image";
import { usePathname } from "next/navigation";
import { signOut, useSession } from "next-auth/react";
import { useState, useEffect } from "react";
import { Menu, X, ChevronDown, User, LogOut, LayoutDashboard, NotebookPen, Smile, FileText } from "lucide-react";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";


export function TopNavigation() {
    const { data: session } = useSession();
    const pathname = usePathname();
    const [isOpen, setIsOpen] = useState(false);
    const [isMounted, setIsMounted] = useState(false);

    useEffect(() => {
        setIsMounted(true);
    }, []);

    if (!isMounted) {
        return null;
    }

    const userRole = session?.user?.role;

    const isActive = (path: string) => pathname === path;

    const getNavLinks = () => {
        if (userRole === "teacher" || userRole === "TEACHER") {
            return [
                {
                    name: "Dashboard Overview",
                    href: "/teacher/dashboard",
                    icon: LayoutDashboard,
                    active: isActive("/teacher/dashboard"),
                },
                {
                    name: "Student Journals",
                    href: "/teacher/journals",
                    icon: NotebookPen,
                    active: isActive("/teacher/journals"),
                },
            ];
        } else {
            return [
                {
                    name: "Dashboard",
                    href: "/student/dashboard",
                    icon: LayoutDashboard,
                    active: isActive("/student/dashboard"),
                },
                {
                    name: "Mood Check",
                    href: "/student/mood",
                    icon: Smile,
                    active: isActive("/student/mood"),
                },
                {
                    name: "Journal",
                    href: "/student/journals",
                    icon: FileText,
                    active: isActive("/student/journals"),
                },
            ];
        }
    };

    const navLinks = getNavLinks();

    return (
        <nav className="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50 font-sans">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="flex justify-between h-20">
                    <div className="flex items-center">
                        {/* Logo */}
                        <Link href="/" className="flex items-center gap-2.5 group shrink-0">
                            <div className="w-9 h-9 relative">
                                <Image
                                    src="/images/serenity-hub-logo.png"
                                    alt="SerenityHub"
                                    fill
                                    className="object-contain"
                                />
                            </div>
                            <span className="text-xl font-bold bg-gradient-to-r from-teal-600 to-indigo-600 bg-clip-text text-transparent tracking-wide">
                                SerenityHub
                            </span>
                        </Link>

                        {/* Desktop Navigation */}
                        <div className="hidden sm:ml-12 sm:flex sm:space-x-4 items-center h-full">
                            {navLinks.map((link) => (
                                <Link
                                    key={link.href}
                                    href={link.href}
                                    className={`inline-flex items-center px-4 py-2 rounded-full text-sm font-medium transition-colors duration-200 gap-2 h-10 ${link.active
                                        ? "bg-teal-50 text-teal-700"
                                        : "text-gray-500 hover:text-gray-700 hover:bg-gray-50"
                                        }`}
                                >
                                    <link.icon className="w-4 h-4" />
                                    {link.name}
                                </Link>
                            ))}
                        </div>
                    </div>

                    {/* Settings Dropdown */}
                    <div className="hidden sm:flex sm:items-center sm:ml-6">
                        <DropdownMenu>
                            <DropdownMenuTrigger asChild>
                                <button className="flex items-center gap-2 px-3 py-2 rounded-full border border-gray-200 bg-gray-50 hover:bg-white hover:shadow-sm transition-all text-sm font-medium text-gray-700 focus:outline-none">
                                    <div className="w-6 h-6 rounded-full bg-teal-500 flex items-center justify-center text-white text-xs">
                                        {session?.user?.name?.[0] || "U"}
                                    </div>
                                    <span className="max-w-[100px] truncate">
                                        {session?.user?.name}
                                    </span>
                                    <ChevronDown className="w-4 h-4 text-gray-400" />
                                </button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end" className="w-56">
                                <DropdownMenuLabel>
                                    <div className="flex flex-col space-y-1">
                                        <p className="text-sm font-medium leading-none">
                                            {session?.user?.name}
                                        </p>
                                        <p className="text-xs leading-none text-muted-foreground">
                                            {session?.user?.email}
                                        </p>
                                    </div>
                                </DropdownMenuLabel>
                                <DropdownMenuSeparator />
                                <DropdownMenuItem asChild>
                                    <Link href="/my-profile" className="cursor-pointer flex items-center gap-2">
                                        <User className="w-4 h-4" /> Profile
                                    </Link>
                                </DropdownMenuItem>
                                <DropdownMenuSeparator />
                                <DropdownMenuItem
                                    className="text-red-600 focus:text-red-600 cursor-pointer flex items-center gap-2"
                                    onClick={() => signOut()}
                                >
                                    <LogOut className="w-4 h-4" /> Log Out
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>

                    {/* Mobile Hamburger */}
                    <div className="flex items-center sm:hidden">
                        <button
                            onClick={() => setIsOpen(!isOpen)}
                            className="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                        >
                            <span className="sr-only">Open main menu</span>
                            {isOpen ? (
                                <X className="block h-6 w-6" aria-hidden="true" />
                            ) : (
                                <Menu className="block h-6 w-6" aria-hidden="true" />
                            )}
                        </button>
                    </div>
                </div>
            </div>

            {/* Mobile Menu */}
            {isOpen && (
                <div className="sm:hidden border-t border-gray-100 bg-white">
                    <div className="pt-2 pb-3 space-y-1">
                        {navLinks.map((link) => (
                            <Link
                                key={link.href}
                                href={link.href}
                                className={`block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors ${link.active
                                    ? "border-teal-500 text-teal-700 bg-teal-50"
                                    : "border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300"
                                    }`}
                                onClick={() => setIsOpen(false)}
                            >
                                <div className="flex items-center gap-3">
                                    <link.icon className="w-5 h-5" />
                                    {link.name}
                                </div>
                            </Link>
                        ))}
                    </div>
                    <div className="pt-4 pb-4 border-t border-gray-200">
                        <div className="flex items-center px-4">
                            <div className="flex-shrink-0">
                                <div className="h-10 w-10 rounded-full bg-teal-500 flex items-center justify-center text-white text-lg font-bold">
                                    {session?.user?.name?.[0] || "U"}
                                </div>
                            </div>
                            <div className="ml-3">
                                <div className="text-base font-medium text-gray-800">
                                    {session?.user?.name}
                                </div>
                                <div className="text-sm font-medium text-gray-500">
                                    {session?.user?.email}
                                </div>
                            </div>
                        </div>
                        <div className="mt-3 space-y-1">
                            <Link
                                href="/my-profile"
                                className="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100"
                                onClick={() => setIsOpen(false)}
                            >
                                Profile
                            </Link>
                            <button
                                onClick={() => signOut()}
                                className="block w-full text-left px-4 py-2 text-base font-medium text-red-600 hover:text-red-800 hover:bg-gray-100"
                            >
                                Log Out
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </nav>
    );
}
