@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form class="form-group" method="POST" action="{{ route('convertCurrency') }}">
                    @csrf
                    <div class="form-group">
                        <label for="currency">Select currency</label>
                        <select name="currency" class="form-control form-control-lg">
                            @foreach($currencies as $keyCurrency => $rate)
                                <option @if(isset($currentCurrency) && $currentCurrency === $keyCurrency) selected @endif value="{{ $keyCurrency }}">{{ $keyCurrency }}</option>
                            @endforeach
                        </select>
                        <label>Rate</label>
                        <input class="form-control" readonly value="{{ $currentRate ?? ''}}">
                    </div>
                    <button type="submit" class="btn btn-primary">Convert</button>
                </form>
            </div>
            <div class="col-md-8 form-group">
                <h3>History</h3>
                <form name="period" method="POST" class="form-group" action="{{ route('currencyHistory') }}">
                    @csrf
                    <label>Currency:</label>
                    <input name="currentCurrency" value="{{ $currentCurrency ?? '' }}" readonly>
                    <select name="period" class="form-control form-control-lg">
                        <option value="day">day</option>
                        <option value="week">week</option>
                    </select>
                    <div>
                        <button type="submit" class="form-control btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            <table class="table col-md-8">
                <thead>
                <tr>
                    <th scope="col">date</th>
                    <th scope="col">rate</th>
                </tr>
                </thead>
                <tbody>
                    @if(isset($historyRates))
                        @foreach($historyRates as $date => $rate)
                            <tr>
                                <td>{{ $date }}</td>
                                <td>{{ $rate[$currentCurrency] }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
