@push('after-styles')
    <style>
        .traffic-light-casing {
            display: inline-block;
            padding: 10px;
            background-color: #222;
            /* Black casing */
            border-radius: 10px;
            border: 2px solid #000;
            text-align: center;
        }

        .traffic-light-row {
            white-space: nowrap;
        }

        .traffic-light {
            width: 30px;
            height: 30px;
            display: inline-block;
            margin: 0 5px;
            border-radius: 50%;
            background-color: #ccc;
            border: 2px solid #333;
            opacity: 0.3;
            transition: opacity 0.3s ease;
        }

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

<div class="traffic-light-casing">
    <div class="traffic-light-row">
        <div class="traffic-light light-red {{ $trafficLightColor === 'red' ? 'on' : '' }}"></div>
        <div class="traffic-light light-amber {{ $trafficLightColor === 'amber' ? 'on' : '' }}"></div>
        <div class="traffic-light light-green {{ $trafficLightColor === 'green' ? 'on' : '' }}"></div>
    </div>
</div>