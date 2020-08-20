@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <table class="table table-bordered">
        <tbody class="text-center">
          @foreach ($board->cells->sortBy('position')->chunk($board->columns) as $chunk)
            <tr>
              @foreach ($chunk as $cell)
                <td>
                  <button
                    class="btn
                    {{ $cell->value === null ? 'btn-secondary' : '' }}
                    {{ $cell->value === 0 ? 'btn-danger' : '' }}
                    {{ $cell->value === 1 ? 'btn-success' : '' }}
                    {{ $cell->value === 2 ? 'btn-warning' : '' }}
                    {{ $cell->value === 3 ? 'btn-info' : '' }}
                    {{ $cell->value === 4 ? 'btn-primary' : '' }}
                    btn-lg btn-block"
                    title="{{ $cell->position }}">
                    {{ $cell->value ?? '*' }}
                  </button>
                </td>
              @endforeach
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
