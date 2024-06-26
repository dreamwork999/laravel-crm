@component('laravel-crm::components.card')

    @component('laravel-crm::components.card-header')

        @slot('title')
            {{ $organisation->name }} <small>@include('laravel-crm::partials.labels',[
                            'labels' => $organisation->labels
                    ])</small>
        @endslot

        @slot('actions')
            <span class="float-right">
                <a type="button" class="btn btn-outline-secondary btn-sm" href="{{ url(route('laravel-crm.organisations.index')) }}"><span class="fa fa-angle-double-left"></span>  {{ ucfirst(__('laravel-crm::lang.back_to_organizations')) }}</a> | 
                @can('create crm deals')
                <a href="{{ url(route('laravel-crm.deals.create',['model' => 'organisation', 'id' => $organisation->id])) }}" alt="Add deal" class="btn btn-success btn-sm"><span class="fa fa-plus" aria-hidden="true"></span> {{ ucfirst(__('laravel-crm::lang.add_new_deal')) }}</a>
                @endcan
                @include('laravel-crm::partials.navs.activities') | 
                @can('edit crm organisations')
                <a href="{{ url(route('laravel-crm.organisations.edit', $organisation)) }}" type="button" class="btn btn-outline-secondary btn-sm"><span class="fa fa-edit" aria-hidden="true"></span></a>
                @endcan
                @can('delete crm organisations')
                <form action="{{ route('laravel-crm.organisations.destroy',$organisation) }}" method="POST" class="form-check-inline mr-0 form-delete-button">
                    {{ method_field('DELETE') }}
                    {{ csrf_field() }}
                    <button class="btn btn-danger btn-sm" type="submit" data-model="{{ __('laravel-crm::lang.organization') }}"><span class="fa fa-trash-o" aria-hidden="true"></span></button>
                </form>
                @endcan    
            </span>
        @endslot

    @endcomponent

    @component('laravel-crm::components.card-body')

        <div class="row">
            <div class="col-sm-6 border-right">
                <h6 class="text-uppercase">{{ ucfirst(__('laravel-crm::lang.details')) }}</h6>
                <hr />
                <dl class="row">
                    <dt class="col-sm-3 text-right">{{ ucfirst(__('laravel-crm::lang.name')) }}</dt>
                    <dd class="col-sm-9">{{ $organisation->name }}</dd>
                    <dt class="col-sm-3 text-right">{{ ucfirst(__('laravel-crm::lang.type')) }}</dt>
                    <dd class="col-sm-9">{{ $organisation->organisationType->name ?? null }}</dd>
                    <dt class="col-sm-3 text-right">{{ ucfirst(__('laravel-crm::lang.description')) }}</dt>
                    <dd class="col-sm-9">{{ $organisation->description }}</dd>
                    @foreach($phones as $phone)
                        <dt class="col-sm-3 text-right">{{ ucfirst($phone->type) }} {{ ucfirst(__('laravel-crm::lang.phone')) }}</dt>
                        <dd class="col-sm-9">
                            <a href="tel:{{ $phone->number }}">{{ $phone->number }}</a> {{ ($phone->primary) ? '(Primary)' : null }}
                        </dd>
                    @endforeach
                    @foreach($emails as $email)
                        <dt class="col-sm-3 text-right">{{ ucfirst($email->type) }} {{ ucfirst(__('laravel-crm::lang.email')) }}</dt>
                        <dd class="col-sm-9">
                            <a href="mailto:{{ $email->address }}">{{ $email->address }}</a> {{ ($email->primary) ? '(Primary)' : null }}
                        </dd>
                    @endforeach
                    @foreach($addresses as $address)
                        <dt class="col-sm-3 text-right">{{ ($address->addressType) ? ucfirst($address->addressType->name).' ' : null }}{{ ucfirst(__('laravel-crm::lang.address')) }}</dt>
                        <dd class="col-sm-9">
                            {{ \VentureDrake\LaravelCrm\Http\Helpers\AddressLine\addressSingleLine($address) }} {{ ($address->primary) ? '(Primary)' : null }}
                        </dd>
                    @endforeach
                </dl>
                <h6 class="text-uppercase mt-4">{{ ucfirst(__('laravel-crm::lang.owner')) }}</h6>
                <hr />
                <dl class="row">
                    <dt class="col-sm-3 text-right">{{ ucfirst(__('laravel-crm::lang.name')) }}</dt>
                    <dd class="col-sm-9"><a href="{{ route('laravel-crm.users.show', $organisation->ownerUser) }}">{{ $organisation->ownerUser->name }}</a></dd>
                </dl>
                @livewire('related-people',[
                    'model' => $organisation
                ])
                @can('view crm deals')
                <h6 class="text-uppercase mt-4 section-h6-title"><span>{{ ucfirst(__('laravel-crm::lang.deals')) }} ({{ $organisation->deals->count() }})</span>@can('create crm deals')<span class="float-right"><a href="{{ url(route('laravel-crm.deals.create',['model' => 'organisation', 'id' => $organisation->id])) }}" class="btn btn-outline-secondary btn-sm"><span class="fa fa-plus" aria-hidden="true"></span></a></span>@endcan</h6>
                <hr />
                @foreach($organisation->deals as $deal)
                    <p>{{ $deal->title }}<br />
                        <small>{{ money($deal->amount, $deal->currency) }}</small></p>
                @endforeach
                @endcan
                @livewire('related-contact-organisations',[
                    'model' => $organisation
                ])
            </div>
            <div class="col-sm-6">
                @include('laravel-crm::partials.activities', [
                    'model' => $organisation
                ])
            </div>
        </div>

    @endcomponent

@endcomponent    