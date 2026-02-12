import { expect, test, describe } from 'vitest'
import { cn } from '@/lib/utils'

describe('Utility Functions', () => {
    test('cn merges tailwind classes correctly', () => {
        const result = cn("bg-red-500", "text-white", "bg-blue-500");
        // tailwind-merge should override bg-red-500 with bg-blue-500
        expect(result).toContain("bg-blue-500");
        expect(result).not.toContain("bg-red-500");
    });
});
