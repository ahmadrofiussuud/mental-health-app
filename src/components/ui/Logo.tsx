import React from "react";

interface LogoProps {
    size?: number;
    className?: string;
    variant?: "color" | "white";
}

export default function Logo({ size = 40, className = "", variant = "color" }: LogoProps) {
    const gradientId = `logo-gradient-${Math.random().toString(36).slice(2, 7)}`;

    return (
        <svg
            width={size}
            height={size}
            viewBox="0 0 100 100"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            className={className}
        >
            <defs>
                <linearGradient id={gradientId} x1="0%" y1="100%" x2="100%" y2="0%">
                    <stop offset="0%" stopColor={variant === "white" ? "#ffffff" : "#14b8a6"} />
                    <stop offset="100%" stopColor={variant === "white" ? "#ffffff" : "#8b5cf6"} />
                </linearGradient>
            </defs>
            {/* Lotus flower - 5 petals */}
            {/* Center petal */}
            <path
                d="M50 15 C50 15, 38 40, 38 55 C38 65, 43 72, 50 75 C57 72, 62 65, 62 55 C62 40, 50 15, 50 15Z"
                fill={`url(#${gradientId})`}
                opacity="0.9"
            />
            {/* Inner left petal */}
            <path
                d="M50 75 C45 70, 25 55, 20 45 C17 38, 20 30, 28 28 C35 26, 42 35, 50 50Z"
                fill={`url(#${gradientId})`}
                opacity="0.8"
            />
            {/* Inner right petal */}
            <path
                d="M50 75 C55 70, 75 55, 80 45 C83 38, 80 30, 72 28 C65 26, 58 35, 50 50Z"
                fill={`url(#${gradientId})`}
                opacity="0.8"
            />
            {/* Outer left petal */}
            <path
                d="M50 80 C40 75, 15 65, 10 55 C6 47, 10 40, 18 38 C26 36, 38 48, 50 65Z"
                fill={`url(#${gradientId})`}
                opacity="0.65"
            />
            {/* Outer right petal */}
            <path
                d="M50 80 C60 75, 85 65, 90 55 C94 47, 90 40, 82 38 C74 36, 62 48, 50 65Z"
                fill={`url(#${gradientId})`}
                opacity="0.65"
            />
            {/* Small base arc */}
            <path
                d="M35 80 Q50 90 65 80"
                stroke={`url(#${gradientId})`}
                strokeWidth="3"
                fill="none"
                strokeLinecap="round"
            />
        </svg>
    );
}
