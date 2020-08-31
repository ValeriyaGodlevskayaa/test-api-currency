@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="POST" action="{{ route('convertCurrency') }}">
                    @csrf
                    <div class="form-group">
                        <label for="fromCurrency">From Currency</label>
                        <select name="fromCurrency" class="form-control form-control-lg">
                            @foreach($currencies as $keyCurrency => $rate)
                                <option value="{{ $keyCurrency }}">{{ $keyCurrency }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="toCurrency">To Currency</label>
                        <select name="toCurrency" class="form-control form-control-lg">
                            @foreach($currencies as $keyCurrency => $rate)
                                <option value="{{ $keyCurrency }}">{{ $keyCurrency }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Convert</button>
                </form>
            </div>
        </div>
    </div>
@endsection
