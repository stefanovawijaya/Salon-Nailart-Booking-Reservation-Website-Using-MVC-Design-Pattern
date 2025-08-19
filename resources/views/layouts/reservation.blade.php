<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Reservasi')</title>
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    .slot-button { pointer-events: auto !important; z-index: 10 !important; position: relative; }
    .calendar-klien div.gray-out {
      background-color: #e0e0e0;
      color: #888;
      pointer-events: none;
      cursor: default;
    }
    .gray-out {
      color: #ccc;
      background-color: #f5f5f5;
      pointer-events: none;
      cursor: not-allowed;
    }
    .selected-date {
      background-color: #f0c0e0;
      border-radius: 50%;
      font-weight: bold;
    }
    .validateButton {
      background-color: rgba(174, 101, 212, 1);
      color: white;
      border: none;
      border-radius: 5px;
      padding: 6px 4px;
      margin-left: 4px;
    }
  </style>
  @stack('head')
</head>
<body>
  @yield('content')
  @stack('scripts')
</body>
</html>