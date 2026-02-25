import { useState, useEffect } from 'react';
import { createPortal } from 'react-dom';

/**
 * Modal — animated portal modal component.
 * Handles smooth pop-in / pop-out transitions via opacity + scale.
 * Safe with CSS transforms on parent elements (uses createPortal to document.body).
 *
 * Props:
 *   isOpen    {boolean}  - controls open/close state
 *   onClose   {function} - called when backdrop is clicked
 *   className {string}   - extra classes for the card wrapper (e.g. "max-w-md max-h-[90vh]")
 *   children  {ReactNode}- the modal card content
 */
export default function Modal({ isOpen, onClose, children, className = '' }) {
    const [mounted, setMounted] = useState(isOpen);
    const [visible, setVisible] = useState(false);

    useEffect(() => {
        if (isOpen) {
            setMounted(true);
            // Double rAF ensures the browser paints the initial hidden state
            // before the transition starts (required for smooth pop-in).
            requestAnimationFrame(() => {
                requestAnimationFrame(() => setVisible(true));
            });
        } else if (mounted) {
            setVisible(false);
            // Keep mounted until exit animation finishes (250ms)
            const timer = setTimeout(() => setMounted(false), 280);
            return () => clearTimeout(timer);
        }
    }, [isOpen]); // eslint-disable-line react-hooks/exhaustive-deps

    if (!mounted) return null;

    return createPortal(
        <div
            className="fixed inset-0 z-50 flex items-center justify-center p-4"
            style={{
                transition: 'opacity 0.25s ease',
                opacity: visible ? 1 : 0,
                pointerEvents: visible ? 'auto' : 'none',
            }}
        >
            {/* Backdrop */}
            <div
                className="absolute inset-0 bg-black/50"
                onClick={onClose}
            />

            {/* Animated card wrapper */}
            <div
                className={`relative w-full ${className}`}
                style={{
                    transition: 'opacity 0.25s ease, transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1)',
                    opacity: visible ? 1 : 0,
                    transform: visible
                        ? 'scale(1) translateY(0px)'
                        : 'scale(0.92) translateY(20px)',
                }}
            >
                {children}
            </div>
        </div>,
        document.body
    );
}

/**
 * ModalLoading — full-card loading overlay for use inside modals.
 * Add as a sibling inside the modal card when data is loading.
 *
 * Usage:
 *   <div className="relative bg-white rounded-2xl ...">
 *     {isLoading && <ModalLoading text="Memuat data..." />}
 *     ...content...
 *   </div>
 */
export function ModalLoading({ text = 'Memuat...' }) {
    return (
        <div className="absolute inset-0 z-10 flex flex-col items-center justify-center bg-white/90 rounded-2xl">
            <div className="w-10 h-10 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mb-3" />
            <p className="text-sm font-semibold text-gray-500">{text}</p>
        </div>
    );
}
