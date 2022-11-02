import { startStimulusApp } from '@symfony/stimulus-bridge';

// Registers Stimulus controllers from controllers.json and in the controllers/ directory
export const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.[jt]sx?$/
));

import axios from "axios";

window.axios = axios;
window.meses = [ { codigo: 1, texto: "Enero" }, { codigo: 2, texto: "Febrero" }, { codigo: 3, texto: "Marzo" }, { codigo: 4, texto: "Abril" }, { codigo: 5, texto: "Mayo" }, { codigo: 6, texto: "Junio" }, { codigo: 7, texto: "Julio" }, { codigo: 8, texto: "Agosto" }, { codigo: 9, texto: "Septiembre" }, { codigo: 10, texto: "Octubre" }, { codigo: 11, texto: "Noviembre" }, { codigo: 12, texto: "Diciembre" } ];
// register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);
