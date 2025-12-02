    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Nepali:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Libraries -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                        },
                        secondary: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                        },
                        accent: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                        },
                        sidebar: { 
                            bg: '#1e293b', 
                            hover: '#334155', 
                            active: '#0ea5e9', 
                            border: '#374151',
                            text: '#f8fafc'
                        },
                        card: { 
                            bg: '#FFFFFF', 
                            shadow: 'rgba(0, 0, 0, 0.05)',
                            border: '#e2e8f0'
                        },
                        table: {
                            header: '#f8fafc',
                            row: '#ffffff',
                            hover: '#f1f5f9',
                            border: '#e2e8f0'
                        }
                    },
                    fontFamily: {
                        sans: ['Noto Sans Nepali', 'system-ui', 'sans-serif']
                    },
                    boxShadow: {
                        'card': '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
                        'sidebar': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                        'table': '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)'
                    },
                    borderRadius: {
                        'card': '8px',
                        'button': '6px'
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Noto Sans Nepali', system-ui, sans-serif;
            background-color: #f8fafc;
            color: #334155;
        }
        .sidebar {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 50;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .sidebar-collapsed {
            width: 70px !important;
        }
        .sidebar-collapsed .sidebar-text {
            opacity: 0;
            transform: translateX(-10px);
            pointer-events: none;
        }
        .sidebar-collapsed .organization-name {
            opacity: 0;
            transform: translateX(-10px);
        }
        .sidebar-collapsed .stats-section {
            opacity: 0;
            transform: translateX(-10px);
            pointer-events: none;
        }
        .sidebar-collapsed .user-section {
            opacity: 0;
            transform: translateX(-10px);
            pointer-events: none;
        }
        .nav-item {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.25rem;
            position: relative;
            overflow: hidden;
        }
        .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(4px);
        }
        .nav-item.active {
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.2), rgba(14, 165, 233, 0.1));
            color: #0ea5e9;
            font-weight: 600;
            border-left: 3px solid #0ea5e9;
        }
        .nav-icon {
            width: 1.25rem;
            text-align: center;
            flex-shrink: 0;
        }
        .sidebar-text {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
        }
        .sidebar-toggle-btn {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-collapsed .sidebar-toggle-btn {
            transform: rotate(180deg);
        }
        .tooltip {
            position: absolute;
            left: 70px;
            background: #1f2937;
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: all 0.2s ease;
            z-index: 1000;
        }
        .sidebar-collapsed .nav-item:hover .tooltip {
            opacity: 1;
        }
        .stat-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .table-container {
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .table-header {
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .table-row {
            transition: background-color 0.15s ease;
        }
        .table-row:hover {
            background-color: #f1f5f9;
        }
        .action-btn {
            transition: all 0.2s ease;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .form-input {
            transition: all 0.2s ease;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 0.75rem 1rem;
        }
        .form-input:focus {
            outline: none;
            border-color: #0ea5e9;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
        }
        .btn-primary {
            background: linear-gradient(135deg, #0ea5e9, #0284c7);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
        }
        @media (max-width: 1023px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
        }
    </style>