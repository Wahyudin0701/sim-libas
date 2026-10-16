@if(request()->routeIs('filament.admin.auth.login'))
<style>
    /* ========================================================
       SIM-LiBas Executive Styling for Admin / Pengurus Login
       ======================================================== */
    
    /* Background Page Styling */
    html, body {
        height: 100%;
    }

    body.fi-body {
        background-color: #071F47 !important;
        background-image: 
            radial-gradient(circle at 15% 15%, rgba(25, 167, 232, 0.16) 0%, transparent 40%),
            radial-gradient(circle at 85% 85%, rgba(242, 169, 0, 0.12) 0%, transparent 45%),
            linear-gradient(180deg, #0B3A85 0%, #071F47 50%, #031229 100%) !important;
        background-attachment: fixed !important;
        background-repeat: no-repeat !important;
        background-size: cover !important;
        min-height: 100vh !important;
        min-height: 100dvh !important;
        position: relative;
    }

    /* Subtle background grid pattern overlay */
    body.fi-body::before {
        content: "";
        position: fixed;
        inset: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.08) 1px, transparent 1px);
        background-size: 28px 28px;
        pointer-events: none;
        opacity: 0.6;
        z-index: 1;
    }

    /* Center Container Layout */
    .fi-simple-layout {
        position: relative;
        z-index: 2;
        min-height: 100vh;
        min-height: 100dvh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: transparent !important;
    }

    .fi-simple-main-ctn {
        position: relative;
        z-index: 10;
        padding: 2rem 1rem !important;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-grow: 1;
    }

    /* Modern Executive Card Container */
    .fi-simple-main {
        max-width: 27.5rem !important;
        width: 100% !important;
        margin: 0 auto !important;
        background: #FFFFFF !important;
        border-radius: 1.25rem !important;
        border: 1px solid rgba(226, 232, 240, 0.95) !important;
        box-shadow: 
            0 25px 60px -15px rgba(2, 12, 27, 0.6), 
            0 0 0 1px rgba(255, 255, 255, 0.2),
            0 10px 25px -5px rgba(11, 58, 133, 0.15) !important;
        padding: 2.25rem 2rem !important;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    @media (min-width: 640px) {
        .fi-simple-main {
            padding: 2.5rem 2.25rem !important;
        }
    }

    /* Universal SVG Constraint: NEVER allow unstyled giant SVGs */
    .fi-simple-main svg:not(.fi-btn-icon) {
        max-width: 1.25rem !important;
        max-height: 1.25rem !important;
    }

    /* Logo Styling */
    .fi-simple-header .fi-logo {
        margin-bottom: 0.75rem !important;
        display: flex;
        justify-content: center;
    }

    .fi-simple-header .fi-logo img {
        height: 4.5rem !important;
        width: auto !important;
        max-width: 240px !important;
        object-fit: contain !important;
        filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.08));
        margin: 0 auto !important;
    }

    /* Heading & Subheading */
    .fi-simple-header-heading {
        font-size: 1.35rem !important;
        font-weight: 800 !important;
        color: #071F47 !important;
        letter-spacing: -0.02em !important;
        text-align: center !important;
        margin-top: 0.25rem !important;
    }

    .fi-simple-header-subheading {
        font-size: 0.8125rem !important;
        color: #64748B !important;
        line-height: 1.45 !important;
        text-align: center !important;
        margin-top: 0.35rem !important;
        max-width: 320px !important;
        margin-left: auto !important;
        margin-right: auto !important;
    }

    /* Custom Badge (Akses Terbatas) */
    .sim-auth-badge-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 1rem;
        margin-bottom: 1.25rem;
    }

    .sim-auth-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.85rem;
        border-radius: 9999px;
        background-color: #EFF6FF;
        border: 1px solid #BFDBFE;
        font-size: 0.6875rem;
        font-weight: 700;
        color: #0B3A85;
        letter-spacing: 0.015em;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    }

    .sim-auth-badge-dot {
        width: 6px;
        height: 6px;
        border-radius: 9999px;
        background-color: #10B981;
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.25);
        flex-shrink: 0;
    }

    .sim-auth-badge-lock {
        width: 12px !important;
        height: 12px !important;
        min-width: 12px !important;
        max-width: 12px !important;
        color: #0B3A85;
        flex-shrink: 0;
    }

    /* Form Fields & Inputs */
    .fi-fo-field-wrp {
        margin-bottom: 1rem !important;
    }

    .fi-fo-field-wrp-label label {
        font-size: 0.75rem !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.03em !important;
        color: #1E293B !important;
    }

    .fi-input-wrp {
        border-radius: 0.75rem !important;
        background-color: #F8FAFC !important;
        border: 1px solid #CBD5E1 !important;
        transition: all 0.15s ease-in-out !important;
    }

    .fi-input-wrp:focus-within {
        background-color: #FFFFFF !important;
        border-color: #0B3A85 !important;
        box-shadow: 0 0 0 3px rgba(11, 58, 133, 0.15) !important;
    }

    /* Primary Submit Button */
    .fi-simple-main button[type="submit"] {
        background: linear-gradient(135deg, #0B3A85 0%, #071F47 100%) !important;
        color: #FFFFFF !important;
        border-radius: 0.75rem !important;
        font-weight: 700 !important;
        font-size: 0.875rem !important;
        padding: 0.85rem 1.25rem !important;
        box-shadow: 0 4px 14px 0 rgba(11, 58, 133, 0.35) !important;
        border: none !important;
        transition: all 0.2s ease !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        gap: 0.5rem !important;
        width: 100% !important;
        margin-top: 0.5rem !important;
    }

    .fi-simple-main button[type="submit"]:hover {
        background: linear-gradient(135deg, #0E48A1 0%, #0B3A85 100%) !important;
        box-shadow: 0 6px 20px 0 rgba(11, 58, 133, 0.45) !important;
        transform: translateY(-1px) !important;
    }

    .fi-simple-main button[type="submit"]:active {
        transform: translateY(0) !important;
    }

    .fi-simple-main button[type="submit"] .fi-btn-icon {
        display: none !important;
    }

    /* Hook After: Divider */
    .sim-auth-after-divider {
        position: relative;
        text-align: center;
        margin-top: 1.75rem;
        margin-bottom: 1.25rem;
    }

    .sim-auth-after-divider::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background-color: #E2E8F0;
        z-index: 1;
    }

    .sim-auth-after-divider span {
        position: relative;
        z-index: 2;
        background: #FFFFFF;
        padding: 0 0.75rem;
        font-size: 0.6875rem;
        font-weight: 700;
        color: #94A3B8;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    /* Hook After: 2 Action Buttons */
    .sim-auth-links-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }

    .sim-auth-link-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.65rem 0.75rem;
        border-radius: 0.75rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-decoration: none !important;
        transition: all 0.2s ease;
        border: 1px solid #CBD5E1;
    }

    .sim-auth-link-citizen {
        background-color: #F8FAFC;
        color: #0B3A85 !important;
    }

    .sim-auth-link-citizen:hover {
        background-color: #EFF6FF;
        border-color: #0B3A85;
        color: #071F47 !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(11, 58, 133, 0.12);
    }

    .sim-auth-link-public {
        background-color: #F8FAFC;
        color: #475569 !important;
    }

    .sim-auth-link-public:hover {
        background-color: #F1F5F9;
        border-color: #94A3B8;
        color: #0F172A !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
    }

    /* Hook After: Help text */
    .sim-auth-help {
        margin-top: 1.25rem;
        text-align: center;
    }

    .sim-auth-help p {
        font-size: 0.75rem;
        color: #64748B;
        margin: 0;
    }

    .sim-auth-help a {
        color: #0B3A85 !important;
        font-weight: 700;
        text-decoration: none !important;
    }

    .sim-auth-help a:hover {
        text-decoration: underline !important;
    }
</style>
@endif
