@extends('layout.master')
@section('title', 'Dashboard')

@section('content')

<div class="container">
<div class="row">

{{-- left col --}}
<div class="col-md-4">

    {{-- users --}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Users</h3>
        </div>

        <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="text-right">ID</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-right">123</td>
                    <td><a href="#">lorem_email@gmail.com</a></td>
                    <td><a href="#">delete</a></td>
                </tr>
                <tr>
                    <td class="text-right">123</td>
                    <td><a href="#">lorem_email@gmail.com</a></td>
                    <td><a href="#">delete</a></td>
                </tr>
                <tr>
                    <td class="text-right">123</td>
                    <td><a href="#">lorem_email@gmail.com</a></td>
                    <td><a href="#">delete</a></td>
                </tr>
                <tr>
                    <td class="text-right">123</td>
                    <td><a href="#">lorem_email@gmail.com</a></td>
                    <td><a href="#">delete</a></td>
                </tr>
            </tbody>
        </table>
        </div>

        <nav class="panel-footer">
            <ul class="pager">
                <li class="previous disabled"><a href="#">newer items</a></li>
                <li class="next"><a href="#">older items</a></li>
            </ul>
        </nav>

    </div>

</div>

{{-- right col --}}
<div class="col-md-8">

    {{-- requests --}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Requests</h3>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="text-right">ID</th>
                        <th>User</th>
                        <th>Template</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-right">123</td>
                        <td><a href="#">lorem_email@gmail.com</a></td>
                        <td><a href="#">Lorem ipsum dolor sit amet</a></td>
                        <td class="text-warning">in progress - <a href="#">cancel</a></td>
                    </tr>
                    <tr>
                        <td class="text-right">123</td>
                        <td><a href="#">lorem_email@gmail.com</a></td>
                        <td><a href="#">Lorem ipsum dolor sit amet</a></td>
                        <td class="text-success">done - <a href="#">download</a></td>
                    </tr>
                    <tr>
                        <td class="text-right">123</td>
                        <td><a href="#">lorem_email@gmail.com</a></td>
                        <td>unknown</td>
                        <td class="text-danger">failed - bad template</td>
                    </tr>
                    <tr>
                        <td class="text-right">123</td>
                        <td><a href="#">lorem_email@gmail.com</a></td>
                        <td><a href="#">Lorem ipsum dolor sit amet</a></td>
                        <td class="text-danger">failed - missing data</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <nav class="panel-footer">
            <ul class="pager">
                <li class="previous disabled"><a href="#">newer items</a></li>
                <li class="next"><a href="#">older items</a></li>
            </ul>
        </nav>

    </div>

</div>

</div>
</div>

@endsection
