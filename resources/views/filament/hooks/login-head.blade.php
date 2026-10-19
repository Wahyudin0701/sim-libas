@if(request()->routeIs('filament.admin.auth.login'))
<style>
    /* ========================================================
       SIM-LiBas Split Screen Styling for Admin / Pengurus Login
       Harmonis & Konsisten 100% dengan Layout Login Warga
       ======================================================== */
    
    html, body {
        height: 100%;
        background-color: #FFFFFF !important;
    }

    body.fi-body {
        background-color: #FFFFFF !important;
        background-image: none !important;
        min-height: 100vh !important;
        min-height: 100dvh !important;
        margin: 0 !important;
        padding: 0 !important;
        position: relative;
    }

    body.fi-body::before {
        display: none !important;
    }

    /* Page container within the split layout */
    .fi-simple-page {
        width: 100% !important;
        max-width: 100% !important;
    }

    .fi-simple-page-content {
        width: 100% !important;
        max-width: 100% !important;
        justify-items: stretch !important;
    }

    /* 1. Form Header (Heading & Subheading) - Rata Kiri Penuh (justify-start / items-start) */
    .fi-simple-header,
    .fi-simple-header * {
        text-align: left !important;
    }

    .fi-simple-header {
        display: flex !important;
        flex-direction: column !important;
        align-items: flex-start !important;
        justify-content: flex-start !important;
        text-align: left !important;
        width: 100% !important;
        max-width: 100% !important;
        margin-bottom: 0.875rem !important;
    }

    .fi-simple-header-heading {
        display: block !important;
        width: 100% !important;
        align-self: flex-start !important;
        font-size: 1.625rem !important;
        font-weight: 800 !important;
        color: #071F47 !important;
        letter-spacing: -0.025em !important;
        text-align: left !important;
        line-height: 1.25 !important;
        margin: 0 0 0.35rem 0 !important;
    }

    @media (min-width: 640px) {
        .fi-simple-header-heading {
            font-size: 1.875rem !important;
        }
    }

    .fi-simple-header-subheading {
        display: block !important;
        width: 100% !important;
        align-self: flex-start !important;
        font-size: 0.8125rem !important;
        color: #5B6B82 !important;
        line-height: 1.5 !important;
        text-align: left !important;
        margin: 0 !important;
        max-width: none !important;
    }

    @media (min-width: 640px) {
        .fi-simple-header-subheading {
            font-size: 0.875rem !important;
        }
    }

    /* 2. Hilangkan penumpukan gap default Filament pada container terluar */
    .fi-simple-page .fi-simple-page-content > .fi-grid {
        gap: 0 !important;
        row-gap: 0 !important;
    }

    /* 3. Custom Badge (Khusus Pengurus & Administrator) */
    .sim-auth-badge-wrapper {
        display: flex;
        justify-content: flex-start;
        margin-top: 0 !important;
        margin-bottom: 1.25rem !important; /* 20px tepat sebelum form */
    }

    .sim-auth-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.75rem;
        border-radius: 9999px;
        background-color: #EFF6FF;
        border: 1px solid #BFDBFE;
        font-size: 0.6875rem;
        font-weight: 700;
        color: #0B3A85;
        letter-spacing: 0.02em;
    }

    .sim-auth-badge-lock {
        width: 13px !important;
        height: 13px !important;
        min-width: 13px !important;
        max-width: 13px !important;
        color: #0B3A85;
        flex-shrink: 0;
    }

    /* 4. Form Layout & Spacing antar baris (Persis space-y-5 / 20px pada Login Warga) */
    .fi-simple-page .fi-sc-form {
        display: flex !important;
        flex-direction: column !important;
        gap: 1.25rem !important; /* 20px antar blok form */
        margin: 0 !important;
    }

    /* Gap antar baris field form (Email -> Password -> Remember) */
    .fi-simple-page .fi-sc-form .fi-sc.fi-sc-has-gap {
        gap: 1.25rem !important; /* 20px konsisten */
        row-gap: 1.25rem !important;
    }

    /* Matikan margin ekstra liar pada field wrapper agar gap tidak berlipat ganda */
    .fi-simple-page .fi-fo-field-wrp,
    .fi-simple-page .fi-fo-field {
        margin-bottom: 0 !important;
        row-gap: 0.5rem !important; /* 8px jarak antara label dan input (persis mb-2) */
    }

    /* Labels - Sinkron dengan Login Warga (Uppercase, bold, dark navy) */
    .fi-simple-page .fi-fo-field-label,
    .fi-simple-page .fi-fo-field-label-content {
        font-size: 0.75rem !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        color: #071F47 !important;
        line-height: 1.2 !important;
    }

    .fi-simple-page .fi-fo-field-label-col,
    .fi-simple-page .fi-fo-field-label-ctn {
        margin-bottom: 0 !important;
        padding: 0 !important;
    }

    /* Modern Soft Input Boxes (Sinkron dengan Login Warga) */
    .fi-simple-page .fi-input-wrp {
        border-radius: 0.75rem !important;
        background-color: #F0F5FA !important;
        border: 1px solid #DDE5F0 !important;
        min-height: 2.875rem !important;
        transition: all 0.15s ease-in-out !important;
        box-shadow: none !important;
    }

    .fi-simple-page .fi-input-wrp:hover {
        background-color: #EBF2F9 !important;
    }

    .fi-simple-page .fi-input-wrp:focus-within {
        background-color: #FFFFFF !important;
        border-color: #0B3A85 !important;
        box-shadow: 0 0 0 3px rgba(11, 58, 133, 0.15) !important;
    }

    .fi-simple-page .fi-input {
        font-size: 0.875rem !important;
        color: #0F1F3D !important;
        padding-top: 0.75rem !important;
        padding-bottom: 0.75rem !important;
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }

    .fi-simple-page .fi-input::placeholder {
        color: #94A3B8 !important;
    }

    /* Checkbox "Ingat saya di perangkat ini" - Font normal case & rapi */
    .fi-simple-page label:has(.fi-checkbox-input) .fi-fo-field-label-content,
    .fi-simple-page .fi-checkbox-input + .fi-fo-field-label-content,
    .fi-simple-page label[for="form.remember"] .fi-fo-field-label-content {
        font-size: 0.75rem !important;
        font-weight: 500 !important;
        text-transform: none !important;
        letter-spacing: normal !important;
        color: #5B6B82 !important;
    }

    .fi-simple-page .fi-checkbox-input {
        border-radius: 0.25rem !important;
        border-color: #DDE5F0 !important;
        width: 1rem !important;
        height: 1rem !important;
        color: #0B3A85 !important;
        cursor: pointer !important;
    }

    /* 5. Primary Submit Button (Masuk ke Panel Pengurus) */
    .fi-simple-page .fi-sc-actions {
        gap: 0 !important;
        margin: 0 !important;
    }

    .fi-simple-page button[type="submit"] {
        background: #0B3A85 !important;
        color: #FFFFFF !important;
        border-radius: 0.75rem !important;
        font-weight: 700 !important;
        font-size: 0.875rem !important;
        padding: 0.875rem 1.25rem !important;
        box-shadow: 0 4px 14px 0 rgba(11, 58, 133, 0.25) !important;
        border: none !important;
        transition: all 0.2s ease !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        gap: 0.5rem !important;
        width: 100% !important;
        margin-top: 0 !important;
        cursor: pointer !important;
    }

    .fi-simple-page button[type="submit"]:hover {
        background: #071F47 !important;
        box-shadow: 0 6px 20px 0 rgba(11, 58, 133, 0.35) !important;
        transform: translateY(-1px) !important;
    }

    .fi-simple-page button[type="submit"]:active {
        transform: translateY(0) !important;
    }

    .fi-simple-page button[type="submit"] .fi-btn-icon {
        display: none !important;
    }

    /* 6. Hook After: Container, Divider & Action Buttons (Persis Login Warga) */
    .sim-auth-after-wrapper {
        margin-top: 0 !important;
    }

    .sim-auth-after-divider {
        position: relative;
        text-align: center;
        margin-top: 1.5rem !important;   /* 24px (persis my-6) */
        margin-bottom: 1.25rem !important;
    }

    .sim-auth-after-divider::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background-color: #DDE5F0;
        z-index: 1;
    }

    .sim-auth-after-divider span {
        position: relative;
        z-index: 2;
        background: #FFFFFF;
        padding: 0 0.75rem;
        font-size: 0.75rem;
        font-weight: 500;
        color: #5B6B82;
    }

    /* 2 Action Buttons (Portal Warga & Ke Beranda) */
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
        border: 1px solid #DDE5F0;
        background-color: #FFFFFF;
        cursor: pointer;
    }

    .sim-auth-link-citizen {
        color: #0B3A85 !important;
    }

    .sim-auth-link-citizen:hover {
        background-color: #EFF6FF;
        border-color: #0B3A85;
        color: #071F47 !important;
    }

    .sim-auth-link-public {
        color: #5B6B82 !important;
    }

    .sim-auth-link-public:hover {
        background-color: #F8FAFC;
        border-color: #0B3A85;
        color: #071F47 !important;
    }

    /* Bantuan Kontak Ketua RT */
    .sim-auth-help {
        margin-top: 1.25rem !important;
        text-align: center;
        font-size: 0.75rem;
        color: #5B6B82;
    }

    .sim-auth-help p {
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
