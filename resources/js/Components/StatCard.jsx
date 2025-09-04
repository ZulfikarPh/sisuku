import { useEffect, useRef } from 'react';
import { animate } from "framer-motion";

export default function StatCard({ value, label, inView }) {
    const ref = useRef(null);

    useEffect(() => {
        if (inView && ref.current) {
            const controls = animate(0, value, {
                duration: 2,
                onUpdate(latest) {
                    ref.current.textContent = Math.round(latest);
                }
            });
            return () => controls.stop();
        }
    }, [inView, value]);

    return (
        <div className="bg-white dark:bg-gray-800/50 backdrop-blur-sm p-6 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 transform hover:scale-105 transition-transform duration-300">
            <p ref={ref} className="text-5xl font-extrabold text-indigo-600 dark:text-indigo-400">0</p>
            <p className="mt-2 text-lg font-medium text-gray-700 dark:text-gray-300">{label}</p>
        </div>
    );
}
