import { createPortal } from 'react-dom';

/**
 * ModalPortal — renders children directly to document.body
 * so that CSS transform on parent elements doesn't clip fixed positioning.
 */
export default function ModalPortal({ children }) {
    return createPortal(children, document.body);
}
