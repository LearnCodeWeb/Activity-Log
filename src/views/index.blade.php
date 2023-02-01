<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Activity Log</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
</head>
<style>
    .container {
        max-width: 1600px;
        width: 90%;
    }

    .main-content {
        overflow-x: hidden;
        overflow-y: scroll;
        height: 100vh;
        width: 100%;
    }

    body {
        min-height: 100vh;
        min-height: -webkit-fill-available;
    }

    html {
        height: -webkit-fill-available;
    }

    main {
        display: flex;
        flex-wrap: nowrap;
        height: 100vh;
        height: -webkit-fill-available;
        max-height: 100vh;
        width: 100%;
    }

    .b-example-divider {
        flex-shrink: 0;
        width: 1.5rem;
        height: 100vh;
        background-color: rgba(0, 0, 0, .1);
        border: solid rgba(0, 0, 0, .15);
        border-width: 1px 0;
        box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
    }

    .bi {
        vertical-align: -.125em;
        pointer-events: none;
        fill: currentColor;
    }

    .dropdown-toggle {
        outline: 0;
    }

    .nav-flush .nav-link {
        border-radius: 0;
    }

    .btn-toggle {
        display: inline-flex;
        align-items: center;
        padding: .25rem .5rem;
        font-weight: 600;
        color: rgba(0, 0, 0, .65);
        background-color: transparent;
        border: 0;
    }

    .btn-toggle:hover,
    .btn-toggle:focus {
        color: rgba(0, 0, 0, .85);
        background-color: #d2f4ea;
    }

    .btn-toggle::before {
        width: 1.25em;
        line-height: 0;
        content: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='rgba%280,0,0,.5%29' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 14l6-6-6-6'/%3e%3c/svg%3e");
        transition: transform .35s ease;
        transform-origin: .5em 50%;
    }

    .btn-toggle[aria-expanded="true"] {
        color: rgba(0, 0, 0, .85);
    }

    .btn-toggle[aria-expanded="true"]::before {
        transform: rotate(90deg);
    }

    .btn-toggle-nav a {
        display: inline-flex;
        padding: .1875rem .5rem;
        margin-top: .125rem;
        margin-left: 1.25rem;
        text-decoration: none;
    }

    .btn-toggle-nav a:hover,
    .btn-toggle-nav a:focus {
        background-color: #d2f4ea;
    }

    .scrollarea {
        overflow-y: auto;
    }

    .fw-semibold {
        font-weight: 600;
    }

    .lh-tight {
        line-height: 1.25;
    }
</style>

<body>
    <main>
        <div class="d-flex flex-column flex-shrink-0 p-3 shadow border border-r sidebar" style="width: 250px;">
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-decoration-none">
                <span class="fs-4">NFS Activity Log</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('lcw_activity_log_index') }}" class="nav-link active" aria-current="page">
                        <i class="bi bi-card-text"></i> All Logs
                    </a>
                </li>
            </ul>
            <hr>
            <a href="{{ url('/') }}" class="d-flex align-items-center text-decoration-none">
                <strong>Visit Website</strong>
            </a>
        </div>


        <div class="main-content">
            <div class="p-4">
                <h1>User Activities</h1>
                <p class="fs-5 col-md-8">This view display all the log you have in the activity log master table. I
                    provide some search to find the log activity.</p>

                <div class="row">
                    <div class="col-sm-12">
                        <form method="GET" action="{{ route('lcw_activity_log_index') }}">
                            <div class="card border-secondary">
                                <h5 class="card-header bg-dark text-white">Search</h5>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-3 mb-3">
                                            <label>Create Date</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control date" name="from_create_date"
                                                    value="{{ request('from_create_date') }}" id="from_create_date"
                                                    placeholder="Date from" readonly>
                                                <span class="input-group-text">-</span>
                                                <input type="text" class="form-control date" name="to_create_date"
                                                    value="{{ request('to_create_date') }}" id="to_create_date"
                                                    placeholder="Date to" readonly>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 mb-3">
                                            <label>Log Text</label>
                                            <input type="text" class="form-control" name="log" id="log"
                                                value="{{ request('log') }}" placeholder="Enter log text here">
                                        </div>
                                        <div class="col-sm-3 mb-3">
                                            <label>User</label>
                                            <select type="text" class="form-control selectpicker" name="user_id"
                                                id="user_id" data-live-search="true">
                                                <option value="">Choose</option>
                                                @foreach ($user as $id => $name)
                                                    <option value="{{ $id }}"
                                                        @if (request('user_id') == $id) {{ 'selected' }} @endif>
                                                        {{ strtoupper($name) . ' (' . $id . ')' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i>
                                        Search</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="mt-3">
                        @if (!empty($log))
                            {{ $log->links() }}
                        @endif
                    </div>

                    <div class="col-sm-12">
                        <div class="card border-secondary rounded-0">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead class="bg-dark text-white">
                                            <tr>
                                                <th>Sr#</th>
                                                <th>Log Text</th>
                                                <th>Server IP</th>
                                                <th>User IP</th>
                                                <th>Page Deatil</th>
                                                <th>Parameters</th>
                                                <th>User</th>
                                                <th>Datetime</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="bg-dark text-white">
                                            <tr>
                                                <th>Sr#</th>
                                                <th>Log Text</th>
                                                <th>Server IP</th>
                                                <th>User IP</th>
                                                <th>Page Deatil</th>
                                                <th>Parameters</th>
                                                <th>User</th>
                                                <th>Datetime</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            @if (!$log->isEmpty())
                                                @foreach ($log as $key => $item)
                                                    @php
                                                        $routeDetail = json_decode($item['route_detail'], true);
                                                        $userDetail = json_decode($item['user'], true);
                                                        $parametersDetail = json_decode($item['query_string'], true);
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $item['log'] }}</td>
                                                        <td>{{ $item['server_ip'] }}</td>
                                                        <td>{{ $item['user_ip'] }}</td>
                                                        <td>
                                                            @if (!empty($routeDetail))
                                                                <table class="table table-sm table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Keys</th>
                                                                            <th>Values</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($routeDetail as $column => $value)
                                                                            @php
                                                                                if (is_array($value)) {
                                                                                    $value = implode(',', $value);
                                                                                }
                                                                            @endphp
                                                                            <tr>
                                                                                <td>{!! $column !!}</td>
                                                                                <td>{!! $value !!}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (!empty($parametersDetail))
                                                                <table class="table table-sm table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Keys</th>
                                                                            <th>Values</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($parametersDetail as $column => $value)
                                                                            @php
                                                                                if (is_array($value)) {
                                                                                    $value = implode(',', $value);
                                                                                }
                                                                            @endphp
                                                                            <tr>
                                                                                <td>{!! $column !!}</td>
                                                                                <td>{!! $value !!}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (!empty($userDetail))
                                                                <table class="table table-sm table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Keys</th>
                                                                            <th>Values</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($userDetail as $column => $value)
                                                                            @php
                                                                                if (is_array($value)) {
                                                                                    $value = implode(',', $value);
                                                                                }
                                                                            @endphp
                                                                            <tr>
                                                                                <td>{!! $column !!}</td>
                                                                                <td>{!! $value !!}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            @endif
                                                        </td>
                                                        <td>{!! date('Y-m-d H:i:s', strtotime($item['created_at'])) !!}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="8" class="text-center">No data found...!</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        @if (!empty($log))
                            {{ $log->links() }}
                        @endif
                    </div>

                </div>
                <footer class="pt-5 my-5 text-muted border-top">
                    Created by <b>Khalid Zaid Bin</b> for <a href="https://www.foula-store.jp/" target="_blank">Foula
                        Store</a> · © 2023
                </footer>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

    <script>
        $(document).ready(function() {
            $('body').on('focus', ".date", function() {
                $(this).datepicker({
                    dateFormat: 'yy-mm-dd',
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "-80:+10"
                });
            });
            $('.selectpicker').selectpicker();
        });
        /* global bootstrap: false */
        (function() {
            'use strict'
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl)
            })
        })()
    </script>
</body>

</html>
