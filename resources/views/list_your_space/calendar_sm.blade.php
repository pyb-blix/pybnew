<div class="responsive-calendar">
	<input type="hidden" value="{{date('Y-m', $local_date)}}" id="month-dropdown_value">
	<div class="calendarMonthHeader d-flex mb-3">
		<a class="previousMonth text-center" data-year="{{$prev_year}}" data-month="{{$prev_month}}" href="javascript:void(0);">
			<i class="icon icon-chevron-left"></i>
		</a>
		<a class="nextMonth text-center" data-year="{{$next_year}}" data-month="{{$next_month}}" href="javascript:void(0);">
			<i class="icon icon-chevron-right"></i>
		</a>
		<div class="select flex-grow-1 loading-wrapper">
			{!! Form::select('year_month', $year_month, date('Y-m', $local_date), ['id' => 'month-dropdown', 'class' => 'h-100']) !!}
		</div>
	</div>
	<div class="calendarDaysHeader text-right">
		<ul>
			<li>
				{{trans('messages.lys.Monday')}}
			</li>
			<li>
				{{trans('messages.lys.Tuesday')}}
			</li>
			<li>
				{{trans('messages.lys.Wednesday')}}
			</li>
			<li>
				{{trans('messages.lys.Thursday')}}
			</li>
			<li>
				{{trans('messages.lys.Friday')}}
			</li>
			<li>
				{{trans('messages.lys.Saturday')}}
			</li>
			<li>
				{{trans('messages.lys.Sunday')}}
			</li>
		</ul>
	</div>
	<div class="card">
		<ul class="calendarDates">
			@foreach($calendar_data as $data)
			<li class="tile {{@$data['class']}}" id="{{@$data['date']}}" data-day="{{@$data['day']}}" data-month="" data-year="">
				<div class="date">
					<span>
						{{@$data['day']}}
					</span>
					@if($data['date'] == date('Y-m-d'))
					<span class="today-label">
						{{trans('messages.lys.today')}}
					</span>
					@endif
				</div>
			</li>
			@endforeach
		</ul>
	</div>
</div>
