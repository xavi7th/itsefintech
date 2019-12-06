@extends('admin::layouts.master')
@section('contents')
@stop
@section('customCSS')
<style>
	.slide-left-enter-active,
	.slide-left-leave-active,
	.slide-right-enter-active,
	.slide-right-leave-active {
		transition-duration: 0.5s;
		transition-property: height, opacity, transform;
		transition-timing-function: cubic-bezier(0.55, 0, 0.1, 1);
		overflow: hidden;
	}

	.slide-left-enter,
	.slide-right-leave-active {
		opacity: 0;
		transform: translate(2em, 0);
	}

	.slide-left-leave-active,
	.slide-right-enter {
		opacity: 0;
		transform: translate(-2em, 0);
	}

	.slide-out-in-enter-active {
		transition: all .3s ease;
	}

	.slide-out-in-leave-active {
		transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
	}

	.slide-out-in-enter,
	.slide-out-in-leave-to {
		transform: translateX(80%);
		opacity: 0;
	}
</style>
@endsection
