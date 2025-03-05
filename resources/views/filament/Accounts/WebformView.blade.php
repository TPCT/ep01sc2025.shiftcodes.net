<div class="grid gap-6 md:grid-cols-3">
    <div>
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">@lang("Account Name")</label>
        <input disabled class="disabled bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{$record->account?->title}}"/>
    </div>
{{--    <div>--}}
{{--        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">@lang("Branch Name")</label>--}}
{{--        <input disabled class="disabled bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{$record->branch?->name}}"/>--}}
{{--    </div>--}}
    <div>
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">@lang("Currency Name")</label>
        <input disabled class="disabled bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{$record->currency?->title}}"/>
    </div>
    <div>
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">@lang("Martial Status")</label>
        <input disabled class="disabled bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{$record->martialStatus?->title}}"/>
    </div>
    <div>
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">@lang("Accommodation Type")</label>
        <input disabled class="disabled bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{$record->accommodationType?->title}}"/>
    </div>
    <div>
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">@lang("Labor Sector")</label>
        <input disabled class="disabled bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{$record->laborSector?->title}}"/>
    </div>
    <div>
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">@lang("Scientific Section")</label>
        <input disabled class="disabled bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{$record->scientificStatus?->title}}"/>
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">@lang("Document Type")</label>
        <input disabled class="disabled bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{$record->documentType?->title}}"/>
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">@lang("Document Number")</label>
        <input disabled class="disabled bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{$record->document_number}}"/>
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">@lang("Document Place Of Issue")</label>
        <input disabled class="disabled bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{$record->document_place_of_issue}}"/>
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">@lang("Document Release Date")</label>
        <input disabled class="disabled bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{$record->document_release_date}}"/>
    </div>

    @foreach($record->getAttributes() as $attribute => $value)
        @continue(in_array($attribute, ['id', 'account_id', 'created_at', 'updated_at', 'document_type_id', 'document_number', 'document_place_of_issue', 'document_release_date', 'branch_id', 'martial_status_id', 'accommodation_type_id', 'labor_sector_id', 'scientific_status_id', 'currency_id']) || !$value)
        <div>
            <label  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ucfirst(str_replace('_', ' ', __($attribute)))}}</label>
            <input disabled class="disabled bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{$value}}" />
        </div>
    @endforeach
</div>

