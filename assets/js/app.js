var $ = require('jquery');
/* on stocke jquery dans la variable $ pour pouvoir l'appeler facilement par la suite */

global.$ = global.jQuery = $;
/* on place $ dans une variable globale pour pouvoir l'utiliser partout car encore encapsule tout dans une fonction, donc $ ne serait sinon pas accessible partout et le calendrier n'apparait pas dans les résa  */

require('bootstrap');
/* bootstrap est installé dans node modules */
