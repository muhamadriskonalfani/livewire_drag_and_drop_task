<div>
    @if (!$openInformation)
        <div wire:click="$set('openInformation', true)" style="cursor: default;">
            Care about people's approval and you will be their prisoner.
        </div>
    @else
        <pre>{{ $fileContent }}</pre>
    @endif
</div>
