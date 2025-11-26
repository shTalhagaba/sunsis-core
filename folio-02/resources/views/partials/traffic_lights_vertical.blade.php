@push('after-styles')
    <style>
        .traffic-light-container {
            display: inline-block;
            padding: 10px;
            background-color: #222;
            border-radius: 10px;
        }

        .traffic-light {
            width: 30px;
            height: 30px;
            margin: 5px auto;
            border-radius: 50%;
            background-color: #ccc;
            border: 2px solid #333;
            opacity: 0.3;
            transition: opacity 0.3s ease;
        }

        /* Lights on */
        .light-red.on {
            background-color: red;
            opacity: 1;
        }

        .light-amber.on {
            background-color: orange;
            opacity: 1;
        }

        .light-green.on {
            background-color: green;
            opacity: 1;
        }
    </style>
@endpush

<div class="traffic-light-container text-center">
    <div class="traffic-light light-red {{ $trafficLightColor === 'red' ? 'on' : '' }}"></div>
    <div class="traffic-light light-amber {{ $trafficLightColor === 'amber' ? 'on' : '' }}"></div>
    <div class="traffic-light light-green {{ $trafficLightColor === 'green' ? 'on' : '' }}"></div>
</div>