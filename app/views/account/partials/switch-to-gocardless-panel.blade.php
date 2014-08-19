<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">Switch to a Direct Debit</h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <p class="lead">
                    We would be really grateful if you could switch to a monthly direct debit payment
                </p>
                <p>
                    Dealing with bank transfers takes up valuable time and PayPal charges us huge fees while Direct Debit payments are quick and fully automated.<br />
                    Switching only takes a minute, just follow the link below to the <a href="https://gocardless.com/security" target="_blank">GoCardless</a> website (the company handling our DD payments) and complete the simple form.<br />
                    <br />
                    <a href="{{ route('account.subscription.create', $user->id) }}" class="btn btn-primary">Setup a Direct Debit for &pound;{{ round($user->monthly_subscription) }}</a>
                    <small><a href="{{ route('account.edit', $user->id) }}">Change your monthly amount</a></small><br />
                    <br />
                    The direct debit date will be today so if you have just made a subscription payment its probably worth waiting a bit.<br />
                    You can cancel the direct debit at any point through this website, the GoCardless website or your bank giving you full control over the payments.<br />
                    <small>By switching you will also protected by the <a href="https://gocardless.com/direct-debit/guarantee/">direct debit grantee.</a></small>
                </p>
            </div>
        </div>
    </div>
</div>