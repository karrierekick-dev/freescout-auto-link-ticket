<form class="form-horizontal margin-top margin-bottom" method="POST" action="">
    {{ csrf_field() }}

    <div class="form-group">
        <label for="autolinkticket_patterns" class="col-sm-2 control-label">{{ __('Trigger Patterns') }}</label>
        <div class="col-sm-6">
            <textarea id="autolinkticket_patterns" name="settings[{{ \Modules\AutoLinkTicket\Services\LinkTicketService::OPTION_PATTERNS }}]" class="form-control" rows="5">{{ $settings[\Modules\AutoLinkTicket\Services\LinkTicketService::OPTION_PATTERNS] }}</textarea>
            <p class="form-help">
                {{ __('One pattern per line. A link to the corresponding ticket is created for every number directly preceded by one of these patterns.') }}<br/>
                {{ __('Examples:') }}<br/>
                ● <code>#</code> &rarr; <code>#123</code><br/>
                ● <code>Case</code> &rarr; <code>Case 123</code> {{ __('or') }} <code>Case123</code> ({{ __('case-insensitive, a space before the number is optional') }})<br/>
                ● <code>XYZ-</code> &rarr; <code>XYZ-123</code>
            </p>
        </div>
    </div>

    <div class="form-group margin-top-0 margin-bottom-0">
        <div class="col-sm-6 col-sm-offset-2">
            <button type="submit" class="btn btn-primary" name="action" value="save">
                {{ __('Save') }}
            </button>
        </div>
    </div>
</form>
