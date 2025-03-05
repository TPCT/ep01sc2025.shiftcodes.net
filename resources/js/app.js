import { disableBodyScroll, enableBodyScroll, clearAllBodyScrollLocks } from 'body-scroll-lock';

import { Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm'

Livewire.start()

window.disableBodyScroll = disableBodyScroll;
window.enableBodyScroll = enableBodyScroll;
window.clearAllBodyScrollLocks = clearAllBodyScrollLocks;

