import { startStimulusApp } from '@symfony/stimulus-bundle';
import GameController from './controllers/game_controller.js';

const app = startStimulusApp();
// register any custom, 3rd party controllers here
app.register('game', GameController);
