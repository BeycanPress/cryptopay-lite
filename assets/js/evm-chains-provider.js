/*! For license information please see evm-chains-provider.js.LICENSE.txt */
  :host {
    z-index: var(--w3m-z-index);
    display: block;
    backface-visibility: hidden;
    will-change: opacity;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    pointer-events: none;
    opacity: 0;
    background-color: var(--wui-cover);
  }

  @keyframes zoom-in {
    0% {
      transform: scale(0.95) translateY(0);
    }
    100% {
      transform: scale(1) translateY(0);
    }
  }

  @keyframes slide-in {
    0% {
      transform: scale(1) translateY(50px);
    }
    100% {
      transform: scale(1) translateY(0);
    }
  }

  wui-card {
    max-width: 360px;
    width: 100%;
    position: relative;
    animation-delay: 0.3s;
    animation-duration: 0.2s;
    animation-name: zoom-in;
    animation-fill-mode: backwards;
    animation-timing-function: var(--wui-ease-out-power-2);
    outline: none;
  }

  wui-flex {
    overflow-x: hidden;
    overflow-y: auto;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
  }

  @media (max-height: 700px) and (min-width: 431px) {
    wui-flex {
      align-items: flex-start;
    }

    wui-card {
      margin: var(--wui-spacing-xxl) 0px;
    }
  }

  @media (max-width: 430px) {
    wui-flex {
      align-items: flex-end;
    }

    wui-card {
      max-width: 100%;
      border-bottom-left-radius: 0;
      border-bottom-right-radius: 0;
      border-bottom: none;
      animation-name: slide-in;
    }
  }
`;var c=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};const l="scroll-lock";let u=class extends s.oi{constructor(){super(),this.unsubscribe=[],this.abortController=void 0,this.open=n.IN.state.open,this.caipAddress=n.AccountController.state.address,this.isSiweEnabled=n.yD.state.isSiweEnabled,this.initializeTheming(),n.ApiController.prefetch(),this.unsubscribe.push(n.IN.subscribeKey("open",(t=>t?this.onOpen():this.onClose())),n.yD.subscribeKey("isSiweEnabled",(t=>{this.isSiweEnabled=t})),n.AccountController.subscribe((t=>this.onNewAccountState(t)))),n.Xs.sendEvent({type:"track",event:"MODAL_LOADED"})}disconnectedCallback(){this.unsubscribe.forEach((t=>t())),this.onRemoveKeyboardListener()}render(){return this.open?s.dy`
          <wui-flex @click=${this.onOverlayClick.bind(this)}>
            <wui-card role="alertdialog" aria-modal="true" tabindex="0">
              <w3m-header></w3m-header>
              <w3m-router></w3m-router>
              <w3m-snackbar></w3m-snackbar>
            </wui-card>
          </wui-flex>
        `:null}async onOverlayClick(t){t.target===t.currentTarget&&await this.handleClose()}async handleClose(){this.isSiweEnabled&&"success"!==n.yD.state.status&&await n.ConnectionController.disconnect(),n.IN.close()}initializeTheming(){const{themeVariables:t,themeMode:e}=n.ThemeController.state,r=i.UiHelperUtil.getColorTheme(e);(0,i.initializeTheming)(t,r)}async onClose(){this.onScrollUnlock(),await this.animate([{opacity:1},{opacity:0}],{duration:200,easing:"ease",fill:"forwards"}).finished,n.SnackController.hide(),this.open=!1,this.onRemoveKeyboardListener()}async onOpen(){this.onScrollLock(),this.open=!0,await this.animate([{opacity:0},{opacity:1}],{duration:200,easing:"ease",fill:"forwards",delay:300}).finished,this.onAddKeyboardListener()}onScrollLock(){const t=document.createElement("style");t.dataset.w3m=l,t.textContent="\n      html, body {\n        touch-action: none;\n        overflow: hidden;\n        overscroll-behavior: contain;\n      }\n      w3m-modal {\n        pointer-events: auto;\n      }\n    ",document.head.appendChild(t)}onScrollUnlock(){const t=document.head.querySelector(`style[data-w3m="${l}"]`);t&&t.remove()}onAddKeyboardListener(){this.abortController=new AbortController;const t=this.shadowRoot?.querySelector("wui-card");t?.focus(),window.addEventListener("keydown",(e=>{if("Escape"===e.key)this.handleClose();else if("Tab"===e.key){const{tagName:r}=e.target;!r||r.includes("W3M-")||r.includes("WUI-")||t?.focus()}}),this.abortController)}onRemoveKeyboardListener(){this.abortController?.abort(),this.abortController=void 0}async onNewAccountState(t){const{isConnected:e,caipAddress:r}=t;if(this.isSiweEnabled){e&&!this.caipAddress&&(this.caipAddress=r),e&&r&&this.caipAddress!==r&&(await n.yD.signOut(),this.onSiweNavigation(),this.caipAddress=r);try{const t=await n.yD.getSession();t&&!e?await n.yD.signOut():e&&!t&&this.onSiweNavigation()}catch(t){e&&this.onSiweNavigation()}}}onSiweNavigation(){this.open?n.RouterController.push("ConnectingSiwe"):n.IN.open({view:"ConnectingSiwe"})}};u.styles=a,c([(0,o.SB)()],u.prototype,"open",void 0),c([(0,o.SB)()],u.prototype,"caipAddress",void 0),c([(0,o.SB)()],u.prototype,"isSiweEnabled",void 0),u=c([(0,i.customElement)("w3m-modal")],u)},48247:(t,e,r)=>{"use strict";r.d(e,{fl:()=>A,iv:()=>c,Ts:()=>E,Qu:()=>x});const n=globalThis,i=n.ShadowRoot&&(void 0===n.ShadyCSS||n.ShadyCSS.nativeShadow)&&"adoptedStyleSheets"in Document.prototype&&"replace"in CSSStyleSheet.prototype,s=Symbol(),o=new WeakMap;class a{constructor(t,e,r){if(this._$cssResult$=!0,r!==s)throw Error("CSSResult is not constructable. Use `unsafeCSS` or `css` instead.");this.cssText=t,this.t=e}get styleSheet(){let t=this.o;const e=this.t;if(i&&void 0===t){const r=void 0!==e&&1===e.length;r&&(t=o.get(e)),void 0===t&&((this.o=t=new CSSStyleSheet).replaceSync(this.cssText),r&&o.set(e,t))}return t}toString(){return this.cssText}}const c=(t,...e)=>{const r=1===t.length?t[0]:e.reduce(((e,r,n)=>e+(t=>{if(!0===t._$cssResult$)return t.cssText;if("number"==typeof t)return t;throw Error("Value passed to 'css' function must be a 'css' function result: "+t+". Use 'unsafeCSS' to pass non-literal values, but take care to ensure page security.")})(r)+t[n+1]),t[0]);return new a(r,t,s)},l=i?t=>t:t=>t instanceof CSSStyleSheet?(t=>{let e="";for(const r of t.cssRules)e+=r.cssText;return(t=>new a("string"==typeof t?t:t+"",void 0,s))(e)})(t):t,{is:u,defineProperty:h,getOwnPropertyDescriptor:d,getOwnPropertyNames:p,getOwnPropertySymbols:f,getPrototypeOf:m}=Object,g=globalThis,y=g.trustedTypes,w=y?y.emptyScript:"",b=g.reactiveElementPolyfillSupport,v=(t,e)=>t,E={toAttribute(t,e){switch(e){case Boolean:t=t?w:null;break;case Object:case Array:t=null==t?t:JSON.stringify(t)}return t},fromAttribute(t,e){let r=t;switch(e){case Boolean:r=null!==t;break;case Number:r=null===t?null:Number(t);break;case Object:case Array:try{r=JSON.parse(t)}catch(t){r=null}}return r}},x=(t,e)=>!u(t,e),_={attribute:!0,type:String,converter:E,reflect:!1,hasChanged:x};Symbol.metadata??=Symbol("metadata"),g.litPropertyMetadata??=new WeakMap;class A extends HTMLElement{static addInitializer(t){this._$Ei(),(this.l??=[]).push(t)}static get observedAttributes(){return this.finalize(),this._$Eh&&[...this._$Eh.keys()]}static createProperty(t,e=_){if(e.state&&(e.attribute=!1),this._$Ei(),this.elementProperties.set(t,e),!e.noAccessor){const r=Symbol(),n=this.getPropertyDescriptor(t,r,e);void 0!==n&&h(this.prototype,t,n)}}static getPropertyDescriptor(t,e,r){const{get:n,set:i}=d(this.prototype,t)??{get(){return this[e]},set(t){this[e]=t}};return{get(){return n?.call(this)},set(e){const s=n?.call(this);i.call(this,e),this.requestUpdate(t,s,r)},configurable:!0,enumerable:!0}}static getPropertyOptions(t){return this.elementProperties.get(t)??_}static _$Ei(){if(this.hasOwnProperty(v("elementProperties")))return;const t=m(this);t.finalize(),void 0!==t.l&&(this.l=[...t.l]),this.elementProperties=new Map(t.elementProperties)}static finalize(){if(this.hasOwnProperty(v("finalized")))return;if(this.finalized=!0,this._$Ei(),this.hasOwnProperty(v("properties"))){const t=this.properties,e=[...p(t),...f(t)];for(const r of e)this.createProperty(r,t[r])}const t=this[Symbol.metadata];if(null!==t){const e=litPropertyMetadata.get(t);if(void 0!==e)for(const[t,r]of e)this.elementProperties.set(t,r)}this._$Eh=new Map;for(const[t,e]of this.elementProperties){const r=this._$Eu(t,e);void 0!==r&&this._$Eh.set(r,t)}this.elementStyles=this.finalizeStyles(this.styles)}static finalizeStyles(t){const e=[];if(Array.isArray(t)){const r=new Set(t.flat(1/0).reverse());for(const t of r)e.unshift(l(t))}else void 0!==t&&e.push(l(t));return e}static _$Eu(t,e){const r=e.attribute;return!1===r?void 0:"string"==typeof r?r:"string"==typeof t?t.toLowerCase():void 0}constructor(){super(),this._$Ep=void 0,this.isUpdatePending=!1,this.hasUpdated=!1,this._$Em=null,this._$Ev()}_$Ev(){this._$Eg=new Promise((t=>this.enableUpdating=t)),this._$AL=new Map,this._$ES(),this.requestUpdate(),this.constructor.l?.forEach((t=>t(this)))}addController(t){(this._$E_??=new Set).add(t),void 0!==this.renderRoot&&this.isConnected&&t.hostConnected?.()}removeController(t){this._$E_?.delete(t)}_$ES(){const t=new Map,e=this.constructor.elementProperties;for(const r of e.keys())this.hasOwnProperty(r)&&(t.set(r,this[r]),delete this[r]);t.size>0&&(this._$Ep=t)}createRenderRoot(){const t=this.shadowRoot??this.attachShadow(this.constructor.shadowRootOptions);return((t,e)=>{if(i)t.adoptedStyleSheets=e.map((t=>t instanceof CSSStyleSheet?t:t.styleSheet));else for(const r of e){const e=document.createElement("style"),i=n.litNonce;void 0!==i&&e.setAttribute("nonce",i),e.textContent=r.cssText,t.appendChild(e)}})(t,this.constructor.elementStyles),t}connectedCallback(){this.renderRoot??=this.createRenderRoot(),this.enableUpdating(!0),this._$E_?.forEach((t=>t.hostConnected?.()))}enableUpdating(t){}disconnectedCallback(){this._$E_?.forEach((t=>t.hostDisconnected?.()))}attributeChangedCallback(t,e,r){this._$AK(t,r)}_$EO(t,e){const r=this.constructor.elementProperties.get(t),n=this.constructor._$Eu(t,r);if(void 0!==n&&!0===r.reflect){const i=(void 0!==r.converter?.toAttribute?r.converter:E).toAttribute(e,r.type);this._$Em=t,null==i?this.removeAttribute(n):this.setAttribute(n,i),this._$Em=null}}_$AK(t,e){const r=this.constructor,n=r._$Eh.get(t);if(void 0!==n&&this._$Em!==n){const t=r.getPropertyOptions(n),i="function"==typeof t.converter?{fromAttribute:t.converter}:void 0!==t.converter?.fromAttribute?t.converter:E;this._$Em=n,this[n]=i.fromAttribute(e,t.type),this._$Em=null}}requestUpdate(t,e,r,n=!1,i){if(void 0!==t){if(r??=this.constructor.getPropertyOptions(t),!(r.hasChanged??x)(n?i:this[t],e))return;this.C(t,e,r)}!1===this.isUpdatePending&&(this._$Eg=this._$EP())}C(t,e,r){this._$AL.has(t)||this._$AL.set(t,e),!0===r.reflect&&this._$Em!==t&&(this._$Ej??=new Set).add(t)}async _$EP(){this.isUpdatePending=!0;try{await this._$Eg}catch(t){Promise.reject(t)}const t=this.scheduleUpdate();return null!=t&&await t,!this.isUpdatePending}scheduleUpdate(){return this.performUpdate()}performUpdate(){if(!this.isUpdatePending)return;if(!this.hasUpdated){if(this.renderRoot??=this.createRenderRoot(),this._$Ep){for(const[t,e]of this._$Ep)this[t]=e;this._$Ep=void 0}const t=this.constructor.elementProperties;if(t.size>0)for(const[e,r]of t)!0!==r.wrapped||this._$AL.has(e)||void 0===this[e]||this.C(e,this[e],r)}let t=!1;const e=this._$AL;try{t=this.shouldUpdate(e),t?(this.willUpdate(e),this._$E_?.forEach((t=>t.hostUpdate?.())),this.update(e)):this._$ET()}catch(e){throw t=!1,this._$ET(),e}t&&this._$AE(e)}willUpdate(t){}_$AE(t){this._$E_?.forEach((t=>t.hostUpdated?.())),this.hasUpdated||(this.hasUpdated=!0,this.firstUpdated(t)),this.updated(t)}_$ET(){this._$AL=new Map,this.isUpdatePending=!1}get updateComplete(){return this.getUpdateComplete()}getUpdateComplete(){return this._$Eg}shouldUpdate(t){return!0}update(t){this._$Ej&&=this._$Ej.forEach((t=>this._$EO(t,this[t]))),this._$ET()}updated(t){}firstUpdated(t){}}A.elementStyles=[],A.shadowRootOptions={mode:"open"},A[v("elementProperties")]=new Map,A[v("finalized")]=new Map,b?.({ReactiveElement:A}),(g.reactiveElementVersions??=[]).push("2.0.2")},88382:(t,e,r)=>{"use strict";r.d(e,{Jb:()=>k,Ld:()=>S,_$LH:()=>$,dy:()=>A,sY:()=>F});const n=globalThis,i=n.trustedTypes,s=i?i.createPolicy("lit-html",{createHTML:t=>t}):void 0,o="$lit$",a=`lit$${(Math.random()+"").slice(9)}$`,c="?"+a,l=`<${c}>`,u=document,h=()=>u.createComment(""),d=t=>null===t||"object"!=typeof t&&"function"!=typeof t,p=Array.isArray,f=t=>p(t)||"function"==typeof t?.[Symbol.iterator],m="[ \t\n\f\r]",g=/<(?:(!--|\/[^a-zA-Z])|(\/?[a-zA-Z][^>\s]*)|(\/?$))/g,y=/-->/g,w=/>/g,b=RegExp(`>|${m}(?:([^\\s"'>=/]+)(${m}*=${m}*(?:[^ \t\n\f\r"'\`<>=]|("|')|))|$)`,"g"),v=/'/g,E=/"/g,x=/^(?:script|style|textarea|title)$/i,_=t=>(e,...r)=>({_$litType$:t,strings:e,values:r}),A=_(1),k=(_(2),Symbol.for("lit-noChange")),S=Symbol.for("lit-nothing"),C=new WeakMap,M=u.createTreeWalker(u,129);function I(t,e){if(!Array.isArray(t)||!t.hasOwnProperty("raw"))throw Error("invalid template strings array");return void 0!==s?s.createHTML(e):e}const P=(t,e)=>{const r=t.length-1,n=[];let i,s=2===e?"<svg>":"",c=g;for(let e=0;e<r;e++){const r=t[e];let u,h,d=-1,p=0;for(;p<r.length&&(c.lastIndex=p,h=c.exec(r),null!==h);)p=c.lastIndex,c===g?"!--"===h[1]?c=y:void 0!==h[1]?c=w:void 0!==h[2]?(x.test(h[2])&&(i=RegExp("</"+h[2],"g")),c=b):void 0!==h[3]&&(c=b):c===b?">"===h[0]?(c=i??g,d=-1):void 0===h[1]?d=-2:(d=c.lastIndex-h[2].length,u=h[1],c=void 0===h[3]?b:'"'===h[3]?E:v):c===E||c===v?c=b:c===y||c===w?c=g:(c=b,i=void 0);const f=c===b&&t[e+1].startsWith("/>")?" ":"";s+=c===g?r+l:d>=0?(n.push(u),r.slice(0,d)+o+r.slice(d)+a+f):r+a+(-2===d?e:f)}return[I(t,s+(t[r]||"<?>")+(2===e?"</svg>":"")),n]};class O{constructor({strings:t,_$litType$:e},r){let n;this.parts=[];let s=0,l=0;const u=t.length-1,d=this.parts,[p,f]=P(t,e);if(this.el=O.createElement(p,r),M.currentNode=this.el.content,2===e){const t=this.el.content.firstChild;t.replaceWith(...t.childNodes)}for(;null!==(n=M.nextNode())&&d.length<u;){if(1===n.nodeType){if(n.hasAttributes())for(const t of n.getAttributeNames())if(t.endsWith(o)){const e=f[l++],r=n.getAttribute(t).split(a),i=/([.?@])?(.*)/.exec(e);d.push({type:1,index:s,name:i[2],strings:r,ctor:"."===i[1]?j:"?"===i[1]?L:"@"===i[1]?U:B}),n.removeAttribute(t)}else t.startsWith(a)&&(d.push({type:6,index:s}),n.removeAttribute(t));if(x.test(n.tagName)){const t=n.textContent.split(a),e=t.length-1;if(e>0){n.textContent=i?i.emptyScript:"";for(let r=0;r<e;r++)n.append(t[r],h()),M.nextNode(),d.push({type:2,index:++s});n.append(t[e],h())}}}else if(8===n.nodeType)if(n.data===c)d.push({type:2,index:s});else{let t=-1;for(;-1!==(t=n.data.indexOf(a,t+1));)d.push({type:7,index:s}),t+=a.length-1}s++}}static createElement(t,e){const r=u.createElement("template");return r.innerHTML=t,r}}function T(t,e,r=t,n){if(e===k)return e;let i=void 0!==n?r._$Co?.[n]:r._$Cl;const s=d(e)?void 0:e._$litDirective$;return i?.constructor!==s&&(i?._$AO?.(!1),void 0===s?i=void 0:(i=new s(t),i._$AT(t,r,n)),void 0!==n?(r._$Co??=[])[n]=i:r._$Cl=i),void 0!==i&&(e=T(t,i._$AS(t,e.values),i,n)),e}class N{constructor(t,e){this._$AV=[],this._$AN=void 0,this._$AD=t,this._$AM=e}get parentNode(){return this._$AM.parentNode}get _$AU(){return this._$AM._$AU}u(t){const{el:{content:e},parts:r}=this._$AD,n=(t?.creationScope??u).importNode(e,!0);M.currentNode=n;let i=M.nextNode(),s=0,o=0,a=r[0];for(;void 0!==a;){if(s===a.index){let e;2===a.type?e=new R(i,i.nextSibling,this,t):1===a.type?e=new a.ctor(i,a.name,a.strings,this,t):6===a.type&&(e=new D(i,this,t)),this._$AV.push(e),a=r[++o]}s!==a?.index&&(i=M.nextNode(),s++)}return M.currentNode=u,n}p(t){let e=0;for(const r of this._$AV)void 0!==r&&(void 0!==r.strings?(r._$AI(t,r,e),e+=r.strings.length-2):r._$AI(t[e])),e++}}class R{get _$AU(){return this._$AM?._$AU??this._$Cv}constructor(t,e,r,n){this.type=2,this._$AH=S,this._$AN=void 0,this._$AA=t,this._$AB=e,this._$AM=r,this.options=n,this._$Cv=n?.isConnected??!0}get parentNode(){let t=this._$AA.parentNode;const e=this._$AM;return void 0!==e&&11===t?.nodeType&&(t=e.parentNode),t}get startNode(){return this._$AA}get endNode(){return this._$AB}_$AI(t,e=this){t=T(this,t,e),d(t)?t===S||null==t||""===t?(this._$AH!==S&&this._$AR(),this._$AH=S):t!==this._$AH&&t!==k&&this._(t):void 0!==t._$litType$?this.g(t):void 0!==t.nodeType?this.$(t):f(t)?this.T(t):this._(t)}k(t){return this._$AA.parentNode.insertBefore(t,this._$AB)}$(t){this._$AH!==t&&(this._$AR(),this._$AH=this.k(t))}_(t){this._$AH!==S&&d(this._$AH)?this._$AA.nextSibling.data=t:this.$(u.createTextNode(t)),this._$AH=t}g(t){const{values:e,_$litType$:r}=t,n="number"==typeof r?this._$AC(t):(void 0===r.el&&(r.el=O.createElement(I(r.h,r.h[0]),this.options)),r);if(this._$AH?._$AD===n)this._$AH.p(e);else{const t=new N(n,this),r=t.u(this.options);t.p(e),this.$(r),this._$AH=t}}_$AC(t){let e=C.get(t.strings);return void 0===e&&C.set(t.strings,e=new O(t)),e}T(t){p(this._$AH)||(this._$AH=[],this._$AR());const e=this._$AH;let r,n=0;for(const i of t)n===e.length?e.push(r=new R(this.k(h()),this.k(h()),this,this.options)):r=e[n],r._$AI(i),n++;n<e.length&&(this._$AR(r&&r._$AB.nextSibling,n),e.length=n)}_$AR(t=this._$AA.nextSibling,e){for(this._$AP?.(!1,!0,e);t&&t!==this._$AB;){const e=t.nextSibling;t.remove(),t=e}}setConnected(t){void 0===this._$AM&&(this._$Cv=t,this._$AP?.(t))}}class B{get tagName(){return this.element.tagName}get _$AU(){return this._$AM._$AU}constructor(t,e,r,n,i){this.type=1,this._$AH=S,this._$AN=void 0,this.element=t,this.name=e,this._$AM=n,this.options=i,r.length>2||""!==r[0]||""!==r[1]?(this._$AH=Array(r.length-1).fill(new String),this.strings=r):this._$AH=S}_$AI(t,e=this,r,n){const i=this.strings;let s=!1;if(void 0===i)t=T(this,t,e,0),s=!d(t)||t!==this._$AH&&t!==k,s&&(this._$AH=t);else{const n=t;let o,a;for(t=i[0],o=0;o<i.length-1;o++)a=T(this,n[r+o],e,o),a===k&&(a=this._$AH[o]),s||=!d(a)||a!==this._$AH[o],a===S?t=S:t!==S&&(t+=(a??"")+i[o+1]),this._$AH[o]=a}s&&!n&&this.O(t)}O(t){t===S?this.element.removeAttribute(this.name):this.element.setAttribute(this.name,t??"")}}class j extends B{constructor(){super(...arguments),this.type=3}O(t){this.element[this.name]=t===S?void 0:t}}class L extends B{constructor(){super(...arguments),this.type=4}O(t){this.element.toggleAttribute(this.name,!!t&&t!==S)}}class U extends B{constructor(t,e,r,n,i){super(t,e,r,n,i),this.type=5}_$AI(t,e=this){if((t=T(this,t,e,0)??S)===k)return;const r=this._$AH,n=t===S&&r!==S||t.capture!==r.capture||t.once!==r.once||t.passive!==r.passive,i=t!==S&&(r===S||n);n&&this.element.removeEventListener(this.name,this,r),i&&this.element.addEventListener(this.name,this,t),this._$AH=t}handleEvent(t){"function"==typeof this._$AH?this._$AH.call(this.options?.host??this.element,t):this._$AH.handleEvent(t)}}class D{constructor(t,e,r){this.element=t,this.type=6,this._$AN=void 0,this._$AM=e,this.options=r}get _$AU(){return this._$AM._$AU}_$AI(t){T(this,t)}}const $={j:o,P:a,A:c,C:1,M:P,L:N,R:f,V:T,D:R,I:B,H:L,N:U,U:j,B:D};(0,n.litHtmlPolyfillSupport)?.(O,R),(n.litHtmlVersions??=[]).push("3.1.0");const F=(t,e,r)=>{const n=r?.renderBefore??e;let i=n._$litPart$;if(void 0===i){const t=r?.renderBefore??null;n._$litPart$=i=new R(e.insertBefore(h(),t),t,void 0,r??{})}return i._$AI(t),i}},2927:(t,e,r)=>{"use strict";r.d(e,{Cb:()=>o,SB:()=>a});var n=r(48247);const i={attribute:!0,type:String,converter:n.Ts,reflect:!1,hasChanged:n.Qu},s=(t=i,e,r)=>{const{kind:n,metadata:s}=r;let o=globalThis.litPropertyMetadata.get(s);if(void 0===o&&globalThis.litPropertyMetadata.set(s,o=new Map),o.set(r.name,t),"accessor"===n){const{name:n}=r;return{set(r){const i=e.get.call(this);e.set.call(this,r),this.requestUpdate(n,i,t)},init(e){return void 0!==e&&this.C(n,void 0,t),e}}}if("setter"===n){const{name:n}=r;return function(r){const i=this[n];e.call(this,r),this.requestUpdate(n,i,t)}}throw Error("Unsupported decorator location: "+n)};function o(t){return(e,r)=>"object"==typeof r?s(t,e,r):((t,e,r)=>{const n=e.hasOwnProperty(r);return e.constructor.createProperty(r,n?{...t,wrapped:!0}:t),n?Object.getOwnPropertyDescriptor(e,r):void 0})(t,e,r)}function a(t){return o({...t,state:!0,attribute:!1})}},74666:(t,e,r)=>{"use strict";r.d(e,{oi:()=>s,iv:()=>n.iv,dy:()=>i.dy});var n=r(48247),i=r(88382);class s extends n.fl{constructor(){super(...arguments),this.renderOptions={host:this},this._$Do=void 0}createRenderRoot(){const t=super.createRenderRoot();return this.renderOptions.renderBefore??=t.firstChild,t}update(t){const e=this.render();this.hasUpdated||(this.renderOptions.isConnected=this.isConnected),super.update(t),this._$Do=(0,i.sY)(e,this.renderRoot,this.renderOptions)}connectedCallback(){super.connectedCallback(),this._$Do?.setConnected(!0)}disconnectedCallback(){super.disconnectedCallback(),this._$Do?.setConnected(!1)}render(){return i.Jb}}s._$litElement$=!0,s.finalized=!0,globalThis.litElementHydrateSupport?.({LitElement:s}),(0,globalThis.litElementPolyfillSupport)?.({LitElement:s}),(globalThis.litElementVersions??=[]).push("4.0.2")},31123:(t,e,r)=>{"use strict";r.r(e),r.d(e,{TransactionUtil:()=>Ji,UiHelperUtil:()=>fr,WuiAccountButton:()=>Sr,WuiAllWalletsImage:()=>Tr,WuiAvatar:()=>vr,WuiButton:()=>Br,WuiCard:()=>At,WuiCardSelect:()=>Vr,WuiCardSelectLoader:()=>Dr,WuiChip:()=>Zr,WuiConnectButton:()=>Yr,WuiCtaButton:()=>en,WuiEmailInput:()=>vn,WuiFlex:()=>yr,WuiGrid:()=>Hi,WuiIcon:()=>Ie,WuiIconBox:()=>_r,WuiIconLink:()=>_n,WuiImage:()=>Te,WuiInputElement:()=>Sn,WuiInputNumeric:()=>In,WuiInputText:()=>yn,WuiLink:()=>Tn,WuiListAccordion:()=>Ri,WuiListContent:()=>Li,WuiListItem:()=>Bn,WuiListWallet:()=>Jn,WuiListWalletTransaction:()=>$i,WuiLoadingHexagon:()=>Re,WuiLoadingSpinner:()=>Le,WuiLoadingThumbnail:()=>$e,WuiLogo:()=>Xn,WuiLogoSelect:()=>ri,WuiNetworkButton:()=>si,WuiNetworkImage:()=>Hr,WuiNoticeCard:()=>Oi,WuiOtp:()=>ci,WuiQrCode:()=>fi,WuiSearchBar:()=>gi,WuiSeparator:()=>Vi,WuiShimmer:()=>He,WuiSnackbar:()=>bi,WuiTabs:()=>xi,WuiTag:()=>Gn,WuiText:()=>Ze,WuiTooltip:()=>ki,WuiTransactionListItem:()=>zn,WuiTransactionListItemLoader:()=>Wn,WuiTransactionVisual:()=>Dn,WuiVisual:()=>dr,WuiVisualThumbnail:()=>Mi,WuiWalletImage:()=>Ir,customElement:()=>xt,initializeTheming:()=>mt,setColorTheme:()=>gt,setThemeVariables:()=>yt});const n=globalThis,i=n.ShadowRoot&&(void 0===n.ShadyCSS||n.ShadyCSS.nativeShadow)&&"adoptedStyleSheets"in Document.prototype&&"replace"in CSSStyleSheet.prototype,s=Symbol(),o=new WeakMap;class a{constructor(t,e,r){if(this._$cssResult$=!0,r!==s)throw Error("CSSResult is not constructable. Use `unsafeCSS` or `css` instead.");this.cssText=t,this.t=e}get styleSheet(){let t=this.o;const e=this.t;if(i&&void 0===t){const r=void 0!==e&&1===e.length;r&&(t=o.get(e)),void 0===t&&((this.o=t=new CSSStyleSheet).replaceSync(this.cssText),r&&o.set(e,t))}return t}toString(){return this.cssText}}const c=t=>new a("string"==typeof t?t:t+"",void 0,s),l=(t,...e)=>{const r=1===t.length?t[0]:e.reduce(((e,r,n)=>e+(t=>{if(!0===t._$cssResult$)return t.cssText;if("number"==typeof t)return t;throw Error("Value passed to 'css' function must be a 'css' function result: "+t+". Use 'unsafeCSS' to pass non-literal values, but take care to ensure page security.")})(r)+t[n+1]),t[0]);return new a(r,t,s)},u=i?t=>t:t=>t instanceof CSSStyleSheet?(t=>{let e="";for(const r of t.cssRules)e+=r.cssText;return c(e)})(t):t,{is:h,defineProperty:d,getOwnPropertyDescriptor:p,getOwnPropertyNames:f,getOwnPropertySymbols:m,getPrototypeOf:g}=Object,y=globalThis,w=y.trustedTypes,b=w?w.emptyScript:"",v=y.reactiveElementPolyfillSupport,E=(t,e)=>t,x={toAttribute(t,e){switch(e){case Boolean:t=t?b:null;break;case Object:case Array:t=null==t?t:JSON.stringify(t)}return t},fromAttribute(t,e){let r=t;switch(e){case Boolean:r=null!==t;break;case Number:r=null===t?null:Number(t);break;case Object:case Array:try{r=JSON.parse(t)}catch(t){r=null}}return r}},_=(t,e)=>!h(t,e),A={attribute:!0,type:String,converter:x,reflect:!1,hasChanged:_};Symbol.metadata??=Symbol("metadata"),y.litPropertyMetadata??=new WeakMap;class k extends HTMLElement{static addInitializer(t){this._$Ei(),(this.l??=[]).push(t)}static get observedAttributes(){return this.finalize(),this._$Eh&&[...this._$Eh.keys()]}static createProperty(t,e=A){if(e.state&&(e.attribute=!1),this._$Ei(),this.elementProperties.set(t,e),!e.noAccessor){const r=Symbol(),n=this.getPropertyDescriptor(t,r,e);void 0!==n&&d(this.prototype,t,n)}}static getPropertyDescriptor(t,e,r){const{get:n,set:i}=p(this.prototype,t)??{get(){return this[e]},set(t){this[e]=t}};return{get(){return n?.call(this)},set(e){const s=n?.call(this);i.call(this,e),this.requestUpdate(t,s,r)},configurable:!0,enumerable:!0}}static getPropertyOptions(t){return this.elementProperties.get(t)??A}static _$Ei(){if(this.hasOwnProperty(E("elementProperties")))return;const t=g(this);t.finalize(),void 0!==t.l&&(this.l=[...t.l]),this.elementProperties=new Map(t.elementProperties)}static finalize(){if(this.hasOwnProperty(E("finalized")))return;if(this.finalized=!0,this._$Ei(),this.hasOwnProperty(E("properties"))){const t=this.properties,e=[...f(t),...m(t)];for(const r of e)this.createProperty(r,t[r])}const t=this[Symbol.metadata];if(null!==t){const e=litPropertyMetadata.get(t);if(void 0!==e)for(const[t,r]of e)this.elementProperties.set(t,r)}this._$Eh=new Map;for(const[t,e]of this.elementProperties){const r=this._$Eu(t,e);void 0!==r&&this._$Eh.set(r,t)}this.elementStyles=this.finalizeStyles(this.styles)}static finalizeStyles(t){const e=[];if(Array.isArray(t)){const r=new Set(t.flat(1/0).reverse());for(const t of r)e.unshift(u(t))}else void 0!==t&&e.push(u(t));return e}static _$Eu(t,e){const r=e.attribute;return!1===r?void 0:"string"==typeof r?r:"string"==typeof t?t.toLowerCase():void 0}constructor(){super(),this._$Ep=void 0,this.isUpdatePending=!1,this.hasUpdated=!1,this._$Em=null,this._$Ev()}_$Ev(){this._$Eg=new Promise((t=>this.enableUpdating=t)),this._$AL=new Map,this._$ES(),this.requestUpdate(),this.constructor.l?.forEach((t=>t(this)))}addController(t){(this._$E_??=new Set).add(t),void 0!==this.renderRoot&&this.isConnected&&t.hostConnected?.()}removeController(t){this._$E_?.delete(t)}_$ES(){const t=new Map,e=this.constructor.elementProperties;for(const r of e.keys())this.hasOwnProperty(r)&&(t.set(r,this[r]),delete this[r]);t.size>0&&(this._$Ep=t)}createRenderRoot(){const t=this.shadowRoot??this.attachShadow(this.constructor.shadowRootOptions);return((t,e)=>{if(i)t.adoptedStyleSheets=e.map((t=>t instanceof CSSStyleSheet?t:t.styleSheet));else for(const r of e){const e=document.createElement("style"),i=n.litNonce;void 0!==i&&e.setAttribute("nonce",i),e.textContent=r.cssText,t.appendChild(e)}})(t,this.constructor.elementStyles),t}connectedCallback(){this.renderRoot??=this.createRenderRoot(),this.enableUpdating(!0),this._$E_?.forEach((t=>t.hostConnected?.()))}enableUpdating(t){}disconnectedCallback(){this._$E_?.forEach((t=>t.hostDisconnected?.()))}attributeChangedCallback(t,e,r){this._$AK(t,r)}_$EO(t,e){const r=this.constructor.elementProperties.get(t),n=this.constructor._$Eu(t,r);if(void 0!==n&&!0===r.reflect){const i=(void 0!==r.converter?.toAttribute?r.converter:x).toAttribute(e,r.type);this._$Em=t,null==i?this.removeAttribute(n):this.setAttribute(n,i),this._$Em=null}}_$AK(t,e){const r=this.constructor,n=r._$Eh.get(t);if(void 0!==n&&this._$Em!==n){const t=r.getPropertyOptions(n),i="function"==typeof t.converter?{fromAttribute:t.converter}:void 0!==t.converter?.fromAttribute?t.converter:x;this._$Em=n,this[n]=i.fromAttribute(e,t.type),this._$Em=null}}requestUpdate(t,e,r,n=!1,i){if(void 0!==t){if(r??=this.constructor.getPropertyOptions(t),!(r.hasChanged??_)(n?i:this[t],e))return;this.C(t,e,r)}!1===this.isUpdatePending&&(this._$Eg=this._$EP())}C(t,e,r){this._$AL.has(t)||this._$AL.set(t,e),!0===r.reflect&&this._$Em!==t&&(this._$Ej??=new Set).add(t)}async _$EP(){this.isUpdatePending=!0;try{await this._$Eg}catch(t){Promise.reject(t)}const t=this.scheduleUpdate();return null!=t&&await t,!this.isUpdatePending}scheduleUpdate(){return this.performUpdate()}performUpdate(){if(!this.isUpdatePending)return;if(!this.hasUpdated){if(this.renderRoot??=this.createRenderRoot(),this._$Ep){for(const[t,e]of this._$Ep)this[t]=e;this._$Ep=void 0}const t=this.constructor.elementProperties;if(t.size>0)for(const[e,r]of t)!0!==r.wrapped||this._$AL.has(e)||void 0===this[e]||this.C(e,this[e],r)}let t=!1;const e=this._$AL;try{t=this.shouldUpdate(e),t?(this.willUpdate(e),this._$E_?.forEach((t=>t.hostUpdate?.())),this.update(e)):this._$ET()}catch(e){throw t=!1,this._$ET(),e}t&&this._$AE(e)}willUpdate(t){}_$AE(t){this._$E_?.forEach((t=>t.hostUpdated?.())),this.hasUpdated||(this.hasUpdated=!0,this.firstUpdated(t)),this.updated(t)}_$ET(){this._$AL=new Map,this.isUpdatePending=!1}get updateComplete(){return this.getUpdateComplete()}getUpdateComplete(){return this._$Eg}shouldUpdate(t){return!0}update(t){this._$Ej&&=this._$Ej.forEach((t=>this._$EO(t,this[t]))),this._$ET()}updated(t){}firstUpdated(t){}}k.elementStyles=[],k.shadowRootOptions={mode:"open"},k[E("elementProperties")]=new Map,k[E("finalized")]=new Map,v?.({ReactiveElement:k}),(y.reactiveElementVersions??=[]).push("2.0.2");const S=globalThis,C=S.trustedTypes,M=C?C.createPolicy("lit-html",{createHTML:t=>t}):void 0,I="$lit$",P=`lit$${(Math.random()+"").slice(9)}$`,O="?"+P,T=`<${O}>`,N=document,R=()=>N.createComment(""),B=t=>null===t||"object"!=typeof t&&"function"!=typeof t,j=Array.isArray,L=t=>j(t)||"function"==typeof t?.[Symbol.iterator],U="[ \t\n\f\r]",D=/<(?:(!--|\/[^a-zA-Z])|(\/?[a-zA-Z][^>\s]*)|(\/?$))/g,$=/-->/g,F=/>/g,z=RegExp(`>|${U}(?:([^\\s"'>=/]+)(${U}*=${U}*(?:[^ \t\n\f\r"'\`<>=]|("|')|))|$)`,"g"),H=/'/g,W=/"/g,q=/^(?:script|style|textarea|title)$/i,V=t=>(e,...r)=>({_$litType$:t,strings:e,values:r}),G=V(1),K=V(2),Z=Symbol.for("lit-noChange"),J=Symbol.for("lit-nothing"),Q=new WeakMap,Y=N.createTreeWalker(N,129);function X(t,e){if(!Array.isArray(t)||!t.hasOwnProperty("raw"))throw Error("invalid template strings array");return void 0!==M?M.createHTML(e):e}const tt=(t,e)=>{const r=t.length-1,n=[];let i,s=2===e?"<svg>":"",o=D;for(let e=0;e<r;e++){const r=t[e];let a,c,l=-1,u=0;for(;u<r.length&&(o.lastIndex=u,c=o.exec(r),null!==c);)u=o.lastIndex,o===D?"!--"===c[1]?o=$:void 0!==c[1]?o=F:void 0!==c[2]?(q.test(c[2])&&(i=RegExp("</"+c[2],"g")),o=z):void 0!==c[3]&&(o=z):o===z?">"===c[0]?(o=i??D,l=-1):void 0===c[1]?l=-2:(l=o.lastIndex-c[2].length,a=c[1],o=void 0===c[3]?z:'"'===c[3]?W:H):o===W||o===H?o=z:o===$||o===F?o=D:(o=z,i=void 0);const h=o===z&&t[e+1].startsWith("/>")?" ":"";s+=o===D?r+T:l>=0?(n.push(a),r.slice(0,l)+I+r.slice(l)+P+h):r+P+(-2===l?e:h)}return[X(t,s+(t[r]||"<?>")+(2===e?"</svg>":"")),n]};class et{constructor({strings:t,_$litType$:e},r){let n;this.parts=[];let i=0,s=0;const o=t.length-1,a=this.parts,[c,l]=tt(t,e);if(this.el=et.createElement(c,r),Y.currentNode=this.el.content,2===e){const t=this.el.content.firstChild;t.replaceWith(...t.childNodes)}for(;null!==(n=Y.nextNode())&&a.length<o;){if(1===n.nodeType){if(n.hasAttributes())for(const t of n.getAttributeNames())if(t.endsWith(I)){const e=l[s++],r=n.getAttribute(t).split(P),o=/([.?@])?(.*)/.exec(e);a.push({type:1,index:i,name:o[2],strings:r,ctor:"."===o[1]?ot:"?"===o[1]?at:"@"===o[1]?ct:st}),n.removeAttribute(t)}else t.startsWith(P)&&(a.push({type:6,index:i}),n.removeAttribute(t));if(q.test(n.tagName)){const t=n.textContent.split(P),e=t.length-1;if(e>0){n.textContent=C?C.emptyScript:"";for(let r=0;r<e;r++)n.append(t[r],R()),Y.nextNode(),a.push({type:2,index:++i});n.append(t[e],R())}}}else if(8===n.nodeType)if(n.data===O)a.push({type:2,index:i});else{let t=-1;for(;-1!==(t=n.data.indexOf(P,t+1));)a.push({type:7,index:i}),t+=P.length-1}i++}}static createElement(t,e){const r=N.createElement("template");return r.innerHTML=t,r}}function rt(t,e,r=t,n){if(e===Z)return e;let i=void 0!==n?r._$Co?.[n]:r._$Cl;const s=B(e)?void 0:e._$litDirective$;return i?.constructor!==s&&(i?._$AO?.(!1),void 0===s?i=void 0:(i=new s(t),i._$AT(t,r,n)),void 0!==n?(r._$Co??=[])[n]=i:r._$Cl=i),void 0!==i&&(e=rt(t,i._$AS(t,e.values),i,n)),e}class nt{constructor(t,e){this._$AV=[],this._$AN=void 0,this._$AD=t,this._$AM=e}get parentNode(){return this._$AM.parentNode}get _$AU(){return this._$AM._$AU}u(t){const{el:{content:e},parts:r}=this._$AD,n=(t?.creationScope??N).importNode(e,!0);Y.currentNode=n;let i=Y.nextNode(),s=0,o=0,a=r[0];for(;void 0!==a;){if(s===a.index){let e;2===a.type?e=new it(i,i.nextSibling,this,t):1===a.type?e=new a.ctor(i,a.name,a.strings,this,t):6===a.type&&(e=new lt(i,this,t)),this._$AV.push(e),a=r[++o]}s!==a?.index&&(i=Y.nextNode(),s++)}return Y.currentNode=N,n}p(t){let e=0;for(const r of this._$AV)void 0!==r&&(void 0!==r.strings?(r._$AI(t,r,e),e+=r.strings.length-2):r._$AI(t[e])),e++}}class it{get _$AU(){return this._$AM?._$AU??this._$Cv}constructor(t,e,r,n){this.type=2,this._$AH=J,this._$AN=void 0,this._$AA=t,this._$AB=e,this._$AM=r,this.options=n,this._$Cv=n?.isConnected??!0}get parentNode(){let t=this._$AA.parentNode;const e=this._$AM;return void 0!==e&&11===t?.nodeType&&(t=e.parentNode),t}get startNode(){return this._$AA}get endNode(){return this._$AB}_$AI(t,e=this){t=rt(this,t,e),B(t)?t===J||null==t||""===t?(this._$AH!==J&&this._$AR(),this._$AH=J):t!==this._$AH&&t!==Z&&this._(t):void 0!==t._$litType$?this.g(t):void 0!==t.nodeType?this.$(t):L(t)?this.T(t):this._(t)}k(t){return this._$AA.parentNode.insertBefore(t,this._$AB)}$(t){this._$AH!==t&&(this._$AR(),this._$AH=this.k(t))}_(t){this._$AH!==J&&B(this._$AH)?this._$AA.nextSibling.data=t:this.$(N.createTextNode(t)),this._$AH=t}g(t){const{values:e,_$litType$:r}=t,n="number"==typeof r?this._$AC(t):(void 0===r.el&&(r.el=et.createElement(X(r.h,r.h[0]),this.options)),r);if(this._$AH?._$AD===n)this._$AH.p(e);else{const t=new nt(n,this),r=t.u(this.options);t.p(e),this.$(r),this._$AH=t}}_$AC(t){let e=Q.get(t.strings);return void 0===e&&Q.set(t.strings,e=new et(t)),e}T(t){j(this._$AH)||(this._$AH=[],this._$AR());const e=this._$AH;let r,n=0;for(const i of t)n===e.length?e.push(r=new it(this.k(R()),this.k(R()),this,this.options)):r=e[n],r._$AI(i),n++;n<e.length&&(this._$AR(r&&r._$AB.nextSibling,n),e.length=n)}_$AR(t=this._$AA.nextSibling,e){for(this._$AP?.(!1,!0,e);t&&t!==this._$AB;){const e=t.nextSibling;t.remove(),t=e}}setConnected(t){void 0===this._$AM&&(this._$Cv=t,this._$AP?.(t))}}class st{get tagName(){return this.element.tagName}get _$AU(){return this._$AM._$AU}constructor(t,e,r,n,i){this.type=1,this._$AH=J,this._$AN=void 0,this.element=t,this.name=e,this._$AM=n,this.options=i,r.length>2||""!==r[0]||""!==r[1]?(this._$AH=Array(r.length-1).fill(new String),this.strings=r):this._$AH=J}_$AI(t,e=this,r,n){const i=this.strings;let s=!1;if(void 0===i)t=rt(this,t,e,0),s=!B(t)||t!==this._$AH&&t!==Z,s&&(this._$AH=t);else{const n=t;let o,a;for(t=i[0],o=0;o<i.length-1;o++)a=rt(this,n[r+o],e,o),a===Z&&(a=this._$AH[o]),s||=!B(a)||a!==this._$AH[o],a===J?t=J:t!==J&&(t+=(a??"")+i[o+1]),this._$AH[o]=a}s&&!n&&this.O(t)}O(t){t===J?this.element.removeAttribute(this.name):this.element.setAttribute(this.name,t??"")}}class ot extends st{constructor(){super(...arguments),this.type=3}O(t){this.element[this.name]=t===J?void 0:t}}class at extends st{constructor(){super(...arguments),this.type=4}O(t){this.element.toggleAttribute(this.name,!!t&&t!==J)}}class ct extends st{constructor(t,e,r,n,i){super(t,e,r,n,i),this.type=5}_$AI(t,e=this){if((t=rt(this,t,e,0)??J)===Z)return;const r=this._$AH,n=t===J&&r!==J||t.capture!==r.capture||t.once!==r.once||t.passive!==r.passive,i=t!==J&&(r===J||n);n&&this.element.removeEventListener(this.name,this,r),i&&this.element.addEventListener(this.name,this,t),this._$AH=t}handleEvent(t){"function"==typeof this._$AH?this._$AH.call(this.options?.host??this.element,t):this._$AH.handleEvent(t)}}class lt{constructor(t,e,r){this.element=t,this.type=6,this._$AN=void 0,this._$AM=e,this.options=r}get _$AU(){return this._$AM._$AU}_$AI(t){rt(this,t)}}const ut={j:I,P,A:O,C:1,M:tt,L:nt,R:L,V:rt,D:it,I:st,H:at,N:ct,U:ot,B:lt};(0,S.litHtmlPolyfillSupport)?.(et,it),(S.litHtmlVersions??=[]).push("3.1.0");class ht extends k{constructor(){super(...arguments),this.renderOptions={host:this},this._$Do=void 0}createRenderRoot(){const t=super.createRenderRoot();return this.renderOptions.renderBefore??=t.firstChild,t}update(t){const e=this.render();this.hasUpdated||(this.renderOptions.isConnected=this.isConnected),super.update(t),this._$Do=((t,e,r)=>{const n=r?.renderBefore??e;let i=n._$litPart$;if(void 0===i){const t=r?.renderBefore??null;n._$litPart$=i=new it(e.insertBefore(R(),t),t,void 0,r??{})}return i._$AI(t),i})(e,this.renderRoot,this.renderOptions)}connectedCallback(){super.connectedCallback(),this._$Do?.setConnected(!0)}disconnectedCallback(){super.disconnectedCallback(),this._$Do?.setConnected(!1)}render(){return Z}}let dt,pt,ft;function mt(t,e){dt=document.createElement("style"),pt=document.createElement("style"),ft=document.createElement("style"),dt.textContent=wt(t).core.cssText,pt.textContent=wt(t).dark.cssText,ft.textContent=wt(t).light.cssText,document.head.appendChild(dt),document.head.appendChild(pt),document.head.appendChild(ft),gt(e)}function gt(t){pt&&ft&&("light"===t?(pt.removeAttribute("media"),ft.media="enabled"):(ft.removeAttribute("media"),pt.media="enabled"))}function yt(t){dt&&pt&&ft&&(dt.textContent=wt(t).core.cssText,pt.textContent=wt(t).dark.cssText,ft.textContent=wt(t).light.cssText)}function wt(t){return{core:l`
      @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
      :root {
        --w3m-color-mix-strength: ${c(t?.["--w3m-color-mix-strength"]?`${t["--w3m-color-mix-strength"]}%`:"0%")};
        --w3m-font-family: ${c(t?.["--w3m-font-family"]||"Inter, Segoe UI, Roboto, Oxygen, Ubuntu, Cantarell, Fira Sans, Droid Sans, Helvetica Neue, sans-serif;")};
        --w3m-font-size-master: ${c(t?.["--w3m-font-size-master"]||"10px")};
        --w3m-border-radius-master: ${c(t?.["--w3m-border-radius-master"]||"4px")};
        --w3m-z-index: ${c(t?.["--w3m-z-index"]||100)};

        --wui-font-family: var(--w3m-font-family);

        --wui-font-size-micro: var(--w3m-font-size-master);
        --wui-font-size-tiny: calc(var(--w3m-font-size-master) * 1.2);
        --wui-font-size-small: calc(var(--w3m-font-size-master) * 1.4);
        --wui-font-size-paragraph: calc(var(--w3m-font-size-master) * 1.6);
        --wui-font-size-large: calc(var(--w3m-font-size-master) * 2);

        --wui-border-radius-5xs: var(--w3m-border-radius-master);
        --wui-border-radius-4xs: calc(var(--w3m-border-radius-master) * 1.5);
        --wui-border-radius-3xs: calc(var(--w3m-border-radius-master) * 2);
        --wui-border-radius-xxs: calc(var(--w3m-border-radius-master) * 3);
        --wui-border-radius-xs: calc(var(--w3m-border-radius-master) * 4);
        --wui-border-radius-s: calc(var(--w3m-border-radius-master) * 5);
        --wui-border-radius-m: calc(var(--w3m-border-radius-master) * 7);
        --wui-border-radius-l: calc(var(--w3m-border-radius-master) * 9);
        --wui-border-radius-3xl: calc(var(--w3m-border-radius-master) * 20);

        --wui-font-weight-light: 400;
        --wui-font-weight-regular: 500;
        --wui-font-weight-medium: 600;
        --wui-font-weight-bold: 700;

        --wui-letter-spacing-large: -0.8px;
        --wui-letter-spacing-paragraph: -0.64px;
        --wui-letter-spacing-small: -0.56px;
        --wui-letter-spacing-tiny: -0.48px;
        --wui-letter-spacing-micro: -0.2px;

        --wui-spacing-0: 0px;
        --wui-spacing-4xs: 2px;
        --wui-spacing-3xs: 4px;
        --wui-spacing-xxs: 6px;
        --wui-spacing-2xs: 7px;
        --wui-spacing-xs: 8px;
        --wui-spacing-1xs: 10px;
        --wui-spacing-s: 12px;
        --wui-spacing-m: 14px;
        --wui-spacing-l: 16px;
        --wui-spacing-2l: 18px;
        --wui-spacing-xl: 20px;
        --wui-spacing-xxl: 24px;
        --wui-spacing-2xl: 32px;
        --wui-spacing-3xl: 40px;
        --wui-spacing-4xl: 90px;

        --wui-icon-box-size-xxs: 14px;
        --wui-icon-box-size-xs: 20px;
        --wui-icon-box-size-sm: 24px;
        --wui-icon-box-size-md: 32px;
        --wui-icon-box-size-lg: 40px;
        --wui-icon-box-size-xl: 64px;

        --wui-icon-size-inherit: inherit;
        --wui-icon-size-xxs: 10px;
        --wui-icon-size-xs: 12px;
        --wui-icon-size-sm: 14px;
        --wui-icon-size-md: 16px;
        --wui-icon-size-mdl: 18px;
        --wui-icon-size-lg: 20px;
        --wui-icon-size-xl: 24px;

        --wui-wallet-image-size-inherit: inherit;
        --wui-wallet-image-size-sm: 40px;
        --wui-wallet-image-size-md: 56px;
        --wui-wallet-image-size-lg: 80px;

        --wui-box-size-md: 100px;
        --wui-box-size-lg: 120px;

        --wui-ease-out-power-2: cubic-bezier(0, 0, 0.22, 1);
        --wui-ease-out-power-1: cubic-bezier(0, 0, 0.55, 1);

        --wui-ease-in-power-3: cubic-bezier(0.66, 0, 1, 1);
        --wui-ease-in-power-2: cubic-bezier(0.45, 0, 1, 1);
        --wui-ease-in-power-1: cubic-bezier(0.3, 0, 1, 1);

        --wui-ease-inout-power-1: cubic-bezier(0.45, 0, 0.55, 1);

        --wui-duration-lg: 200ms;
        --wui-duration-md: 125ms;
        --wui-duration-sm: 75ms;

        --wui-path-network: path(
          'M43.4605 10.7248L28.0485 1.61089C25.5438 0.129705 22.4562 0.129705 19.9515 1.61088L4.53951 10.7248C2.03626 12.2051 0.5 14.9365 0.5 17.886V36.1139C0.5 39.0635 2.03626 41.7949 4.53951 43.2752L19.9515 52.3891C22.4562 53.8703 25.5438 53.8703 28.0485 52.3891L43.4605 43.2752C45.9637 41.7949 47.5 39.0635 47.5 36.114V17.8861C47.5 14.9365 45.9637 12.2051 43.4605 10.7248Z'
        );

        --wui-path-network-lg: path(
          'M78.3244 18.926L50.1808 2.45078C45.7376 -0.150261 40.2624 -0.150262 35.8192 2.45078L7.6756 18.926C3.23322 21.5266 0.5 26.3301 0.5 31.5248V64.4752C0.5 69.6699 3.23322 74.4734 7.6756 77.074L35.8192 93.5492C40.2624 96.1503 45.7376 96.1503 50.1808 93.5492L78.3244 77.074C82.7668 74.4734 85.5 69.6699 85.5 64.4752V31.5248C85.5 26.3301 82.7668 21.5266 78.3244 18.926Z'
        );

        --wui-color-inherit: inherit;

        --wui-color-inverse-100: #fff;
        --wui-color-inverse-000: #000;

        --wui-cover: rgba(20, 20, 20, 0.8);

        --wui-color-modal-bg: var(--wui-color-modal-bg-base);

        --wui-color-blue-100: var(--wui-color-blue-base-100);

        --wui-color-accent-100: var(--wui-color-accent-base-100);
        --wui-color-accent-090: var(--wui-color-accent-base-090);
        --wui-color-accent-080: var(--wui-color-accent-base-080);

        --wui-accent-glass-090: var(--wui-accent-glass-base-090);
        --wui-accent-glass-080: var(--wui-accent-glass-base-080);
        --wui-accent-glass-020: var(--wui-accent-glass-base-020);
        --wui-accent-glass-015: var(--wui-accent-glass-base-015);
        --wui-accent-glass-010: var(--wui-accent-glass-base-010);
        --wui-accent-glass-005: var(--wui-accent-glass-base-005);
        --wui-accent-glass-002: var(--wui-accent-glass-base-002);

        --wui-color-fg-100: var(--wui-color-fg-base-100);
        --wui-color-fg-125: var(--wui-color-fg-base-125);
        --wui-color-fg-150: var(--wui-color-fg-base-150);
        --wui-color-fg-175: var(--wui-color-fg-base-175);
        --wui-color-fg-200: var(--wui-color-fg-base-200);
        --wui-color-fg-225: var(--wui-color-fg-base-225);
        --wui-color-fg-250: var(--wui-color-fg-base-250);
        --wui-color-fg-275: var(--wui-color-fg-base-275);
        --wui-color-fg-300: var(--wui-color-fg-base-300);

        --wui-color-bg-100: var(--wui-color-bg-base-100);
        --wui-color-bg-125: var(--wui-color-bg-base-125);
        --wui-color-bg-150: var(--wui-color-bg-base-150);
        --wui-color-bg-175: var(--wui-color-bg-base-175);
        --wui-color-bg-200: var(--wui-color-bg-base-200);
        --wui-color-bg-225: var(--wui-color-bg-base-225);
        --wui-color-bg-250: var(--wui-color-bg-base-250);
        --wui-color-bg-275: var(--wui-color-bg-base-275);
        --wui-color-bg-300: var(--wui-color-bg-base-300);

        --wui-color-success-100: var(--wui-color-success-base-100);
        --wui-color-error-100: var(--wui-color-error-base-100);

        --wui-icon-box-bg-error-100: var(--wui-icon-box-bg-error-base-100);
        --wui-icon-box-bg-blue-100: var(--wui-icon-box-bg-blue-base-100);
        --wui-icon-box-bg-success-100: var(--wui-icon-box-bg-success-base-100);
        --wui-icon-box-bg-inverse-100: var(--wui-icon-box-bg-inverse-base-100);

        --wui-all-wallets-bg-100: var(--wui-all-wallets-bg-base-100);

        --wui-avatar-border: var(--wui-avatar-border-base);

        --wui-thumbnail-border: var(--wui-thumbnail-border-base);

        --wui-box-shadow-blue: rgba(71, 161, 255, 0.16);
      }

      @supports (background: color-mix(in srgb, white 50%, black)) {
        :root {
          --wui-color-modal-bg: color-mix(
            in srgb,
            var(--w3m-color-mix) var(--w3m-color-mix-strength),
            var(--wui-color-modal-bg-base)
          );

          --wui-box-shadow-blue: color-mix(in srgb, var(--wui-color-accent-100) 16%, transparent);

          --wui-color-accent-090: color-mix(
            in srgb,
            var(--wui-color-accent-base-100) 90%,
            var(--w3m-default)
          );
          --wui-color-accent-080: color-mix(
            in srgb,
            var(--wui-color-accent-base-100) 80%,
            var(--w3m-default)
          );

          --wui-color-accent-090: color-mix(
            in srgb,
            var(--wui-color-accent-base-100) 90%,
            transparent
          );
          --wui-color-accent-080: color-mix(
            in srgb,
            var(--wui-color-accent-base-100) 80%,
            transparent
          );

          --wui-accent-glass-090: color-mix(
            in srgb,
            var(--wui-color-accent-base-100) 90%,
            transparent
          );
          --wui-accent-glass-080: color-mix(
            in srgb,
            var(--wui-color-accent-base-100) 80%,
            transparent
          );
          --wui-accent-glass-020: color-mix(
            in srgb,
            var(--wui-color-accent-base-100) 20%,
            transparent
          );
          --wui-accent-glass-015: color-mix(
            in srgb,
            var(--wui-color-accent-base-100) 15%,
            transparent
          );
          --wui-accent-glass-010: color-mix(
            in srgb,
            var(--wui-color-accent-base-100) 10%,
            transparent
          );
          --wui-accent-glass-005: color-mix(
            in srgb,
            var(--wui-color-accent-base-100) 5%,
            transparent
          );
          --wui-color-accent-002: color-mix(
            in srgb,
            var(--wui-color-accent-base-100) 2%,
            transparent
          );

          --wui-color-fg-100: color-mix(
            in srgb,
            var(--w3m-color-mix) var(--w3m-color-mix-strength),
            var(--wui-color-fg-base-100)
          );
          --wui-color-fg-125: color-mix(
            in srgb,
            var(--w3m-color-mix) var(--w3m-color-mix-strength),
            var(--wui-color-fg-base-125)
          );
          --wui-color-fg-150: color-mix(
            in srgb,
            var(--w3m-color-mix) var(--w3m-color-mix-strength),
            var(--wui-color-fg-base-150)
          );
          --wui-color-fg-175: color-mix(
            in srgb,
            var(--w3m-color-mix) var(--w3m-color-mix-strength),
            var(--wui-color-fg-base-175)
          );
          --wui-color-fg-200: color-mix(
            in srgb,
            var(--w3m-color-mix) var(--w3m-color-mix-strength),
            var(--wui-color-fg-base-200)
          );
          --wui-color-fg-225: color-mix(
            in srgb,
            var(--w3m-color-mix) var(--w3m-color-mix-strength),
            var(--wui-color-fg-base-225)
          );
          --wui-color-fg-250: color-mix(
            in srgb,
            var(--w3m-color-mix) var(--w3m-color-mix-strength),
            var(--wui-color-fg-base-250)
          );
          --wui-color-fg-275: color-mix(
            in srgb,
            var(--w3m-color-mix) var(--w3m-color-mix-strength),
            var(--wui-color-fg-base-275)
          );
          --wui-color-fg-300: color-mix(
            in srgb,
            var(--w3m-color-mix) var(--w3m-color-mix-strength),
            var(--wui-color-fg-base-300)
          );

          --wui-color-bg-100: color-mix(
            in srgb,
            var(--w3m-color-mix) var(--w3m-color-mix-strength),
            var(--wui-color-bg-base-100)
          );
          --wui-color-bg-125: color-mix(
            in srgb,
            var(--w3m-color-mix) var(--w3m-color-mix-strength),
            var(--wui-color-bg-base-125)
          );
          --wui-color-bg-150: color-mix(
            in srgb,
            var(--w3m-color-mix) var(--w3m-color-mix-strength),
            var(--wui-color-bg-base-150)
          );
          --wui-color-bg-175: color-mix(
            in srgb,
            var(--w3m-color-mix) var(--w3m-color-mix-strength),
            var(--wui-color-bg-base-175)
          );
          --wui-color-bg-200: color-mix(
            in srgb,
            var(--w3m-color-mix) var(--w3m-color-mix-strength),
            var(--wui-color-bg-base-200)
          );
          --wui-color-bg-225: color-mix(
            in srgb,
            var(--w3m-color-mix) var(--w3m-color-mix-strength),
            var(--wui-color-bg-base-225)
          );
          --wui-color-bg-250: color-mix(
            in srgb,
            var(--w3m-color-mix) var(--w3m-color-mix-strength),
            var(--wui-color-bg-base-250)
          );
          --wui-color-bg-275: color-mix(
            in srgb,
            var(--w3m-color-mix) var(--w3m-color-mix-strength),
            var(--wui-color-bg-base-275)
          );
          --wui-color-bg-300: color-mix(
            in srgb,
            var(--w3m-color-mix) var(--w3m-color-mix-strength),
            var(--wui-color-bg-base-300)
          );

          --wui-color-success-100: color-mix(
            in srgb,
            var(--w3m-color-mix) var(--w3m-color-mix-strength),
            var(--wui-color-success-base-100)
          );
          --wui-color-error-100: color-mix(
            in srgb,
            var(--w3m-color-mix) var(--w3m-color-mix-strength),
            var(--wui-color-error-base-100)
          );

          --wui-icon-box-bg-error-100: color-mix(
            in srgb,
            var(--w3m-color-mix) var(--w3m-color-mix-strength),
            var(--wui-icon-box-bg-error-base-100)
          );
          --wui-icon-box-bg-accent-100: color-mix(
            in srgb,
            var(--w3m-color-mix) var(--w3m-color-mix-strength),
            var(--wui-icon-box-bg-blue-base-100)
          );
          --wui-icon-box-bg-success-100: color-mix(
            in srgb,
            var(--w3m-color-mix) var(--w3m-color-mix-strength),
            var(--wui-icon-box-bg-success-base-100)
          );
          --wui-icon-box-bg-inverse-100: color-mix(
            in srgb,
            var(--w3m-color-mix) var(--w3m-color-mix-strength),
            var(--wui-icon-box-bg-inverse-base-100)
          );

          --wui-all-wallets-bg-100: color-mix(
            in srgb,
            var(--w3m-color-mix) var(--w3m-color-mix-strength),
            var(--wui-all-wallets-bg-base-100)
          );

          --wui-avatar-border: color-mix(
            in srgb,
            var(--w3m-color-mix) var(--w3m-color-mix-strength),
            var(--wui-avatar-border-base)
          );

          --wui-thumbnail-border: color-mix(
            in srgb,
            var(--w3m-color-mix) var(--w3m-color-mix-strength),
            var(--wui-thumbnail-border-base)
          );
        }
      }
    `,light:l`
      :root {
        --w3m-color-mix: ${c(t?.["--w3m-color-mix"]||"#fff")};
        --w3m-accent: ${c(t?.["--w3m-accent"]||"#47a1ff")};
        --w3m-default: #fff;

        --wui-color-modal-bg-base: #191a1a;

        --wui-color-blue-base-100: #47a1ff;

        --wui-color-accent-base-100: var(--w3m-accent);
        --wui-color-accent-base-090: #59aaff;
        --wui-color-accent-base-080: #6cb4ff;

        --wui-accent-glass-base-090: rgba(71, 161, 255, 0.9);
        --wui-accent-glass-base-080: rgba(71, 161, 255, 0.8);
        --wui-accent-glass-base-020: rgba(71, 161, 255, 0.2);
        --wui-accent-glass-base-015: rgba(71, 161, 255, 0.15);
        --wui-accent-glass-base-010: rgba(71, 161, 255, 0.1);
        --wui-accent-glass-base-005: rgba(71, 161, 255, 0.05);
        --wui-accent-glass-base-002: rgba(71, 161, 255, 0.02);

        --wui-color-fg-base-100: #e4e7e7;
        --wui-color-fg-base-125: #d0d5d5;
        --wui-color-fg-base-150: #a8b1b1;
        --wui-color-fg-base-175: #a8b0b0;
        --wui-color-fg-base-200: #949e9e;
        --wui-color-fg-base-225: #868f8f;
        --wui-color-fg-base-250: #788080;
        --wui-color-fg-base-275: #788181;
        --wui-color-fg-base-300: #6e7777;

        --wui-color-bg-base-100: #141414;
        --wui-color-bg-base-125: #191a1a;
        --wui-color-bg-base-150: #1e1f1f;
        --wui-color-bg-base-175: #222525;
        --wui-color-bg-base-200: #272a2a;
        --wui-color-bg-base-225: #2c3030;
        --wui-color-bg-base-250: #313535;
        --wui-color-bg-base-275: #363b3b;
        --wui-color-bg-base-300: #3b4040;

        --wui-color-success-base-100: #26d962;
        --wui-color-error-base-100: #f25a67;

        --wui-success-glass-001: rgba(38, 217, 98, 0.01);
        --wui-success-glass-002: rgba(38, 217, 98, 0.02);
        --wui-success-glass-005: rgba(38, 217, 98, 0.05);
        --wui-success-glass-010: rgba(38, 217, 98, 0.1);
        --wui-success-glass-015: rgba(38, 217, 98, 0.15);
        --wui-success-glass-020: rgba(38, 217, 98, 0.2);
        --wui-success-glass-025: rgba(38, 217, 98, 0.25);
        --wui-success-glass-030: rgba(38, 217, 98, 0.3);
        --wui-success-glass-060: rgba(38, 217, 98, 0.6);
        --wui-success-glass-080: rgba(38, 217, 98, 0.8);

        --wui-icon-box-bg-error-base-100: #3c2426;
        --wui-icon-box-bg-blue-base-100: #20303f;
        --wui-icon-box-bg-success-base-100: #1f3a28;
        --wui-icon-box-bg-inverse-base-100: #243240;

        --wui-all-wallets-bg-base-100: #222b35;

        --wui-avatar-border-base: #252525;

        --wui-thumbnail-border-base: #252525;

        --wui-gray-glass-001: rgba(255, 255, 255, 0.01);
        --wui-gray-glass-002: rgba(255, 255, 255, 0.02);
        --wui-gray-glass-005: rgba(255, 255, 255, 0.05);
        --wui-gray-glass-010: rgba(255, 255, 255, 0.1);
        --wui-gray-glass-015: rgba(255, 255, 255, 0.15);
        --wui-gray-glass-020: rgba(255, 255, 255, 0.2);
        --wui-gray-glass-025: rgba(255, 255, 255, 0.25);
        --wui-gray-glass-030: rgba(255, 255, 255, 0.3);
        --wui-gray-glass-060: rgba(255, 255, 255, 0.6);
        --wui-gray-glass-080: rgba(255, 255, 255, 0.8);
      }
    `,dark:l`
      :root {
        --w3m-color-mix: ${c(t?.["--w3m-color-mix"]||"#000")};
        --w3m-accent: ${c(t?.["--w3m-accent"]||"#3396ff")};
        --w3m-default: #000;

        --wui-color-modal-bg-base: #fff;

        --wui-color-blue-base-100: #3396ff;

        --wui-color-accent-base-100: var(--w3m-accent);
        --wui-color-accent-base-090: #2d7dd2;
        --wui-color-accent-base-080: #2978cc;

        --wui-accent-glass-base-090: rgba(51, 150, 255, 0.9);
        --wui-accent-glass-base-080: rgba(51, 150, 255, 0.8);
        --wui-accent-glass-base-020: rgba(51, 150, 255, 0.2);
        --wui-accent-glass-base-015: rgba(51, 150, 255, 0.15);
        --wui-accent-glass-base-010: rgba(51, 150, 255, 0.1);
        --wui-accent-glass-base-005: rgba(51, 150, 255, 0.05);
        --wui-accent-glass-base-002: rgba(51, 150, 255, 0.02);

        --wui-color-fg-base-100: #141414;
        --wui-color-fg-base-125: #2d3131;
        --wui-color-fg-base-150: #474d4d;
        --wui-color-fg-base-175: #636d6d;
        --wui-color-fg-base-200: #798686;
        --wui-color-fg-base-225: #828f8f;
        --wui-color-fg-base-250: #8b9797;
        --wui-color-fg-base-275: #95a0a0;
        --wui-color-fg-base-300: #9ea9a9;

        --wui-color-bg-base-100: #ffffff;
        --wui-color-bg-base-125: #f5fafa;
        --wui-color-bg-base-150: #f3f8f8;
        --wui-color-bg-base-175: #eef4f4;
        --wui-color-bg-base-200: #eaf1f1;
        --wui-color-bg-base-225: #e5eded;
        --wui-color-bg-base-250: #e1e9e9;
        --wui-color-bg-base-275: #dce7e7;
        --wui-color-bg-base-300: #d8e3e3;

        --wui-color-success-base-100: #26b562;
        --wui-color-error-base-100: #f05142;

        --wui-success-glass-001: rgba(38, 181, 98, 0.01);
        --wui-success-glass-002: rgba(38, 181, 98, 0.02);
        --wui-success-glass-005: rgba(38, 181, 98, 0.05);
        --wui-success-glass-010: rgba(38, 181, 98, 0.1);
        --wui-success-glass-015: rgba(38, 181, 98, 0.15);
        --wui-success-glass-020: rgba(38, 181, 98, 0.2);
        --wui-success-glass-025: rgba(38, 181, 98, 0.25);
        --wui-success-glass-030: rgba(38, 181, 98, 0.3);
        --wui-success-glass-060: rgba(38, 181, 98, 0.6);
        --wui-success-glass-080: rgba(38, 181, 98, 0.8);

        --wui-icon-box-bg-error-base-100: #f4dfdd;
        --wui-icon-box-bg-blue-base-100: #d9ecfb;
        --wui-icon-box-bg-success-base-100: #daf0e4;
        --wui-icon-box-bg-inverse-base-100: #dcecfc;

        --wui-all-wallets-bg-base-100: #e8f1fa;

        --wui-avatar-border-base: #f3f4f4;

        --wui-thumbnail-border-base: #eaefef;

        --wui-gray-glass-001: rgba(0, 0, 0, 0.01);
        --wui-gray-glass-002: rgba(0, 0, 0, 0.02);
        --wui-gray-glass-005: rgba(0, 0, 0, 0.05);
        --wui-gray-glass-010: rgba(0, 0, 0, 0.1);
        --wui-gray-glass-015: rgba(0, 0, 0, 0.15);
        --wui-gray-glass-020: rgba(0, 0, 0, 0.2);
        --wui-gray-glass-025: rgba(0, 0, 0, 0.25);
        --wui-gray-glass-030: rgba(0, 0, 0, 0.3);
        --wui-gray-glass-060: rgba(0, 0, 0, 0.6);
        --wui-gray-glass-080: rgba(0, 0, 0, 0.8);
      }
    `}}ht._$litElement$=!0,ht.finalized=!0,globalThis.litElementHydrateSupport?.({LitElement:ht}),(0,globalThis.litElementPolyfillSupport)?.({LitElement:ht}),(globalThis.litElementVersions??=[]).push("4.0.2");const bt=l`
  *,
  *::after,
  *::before,
  :host {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-style: normal;
    text-rendering: optimizeSpeed;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    -webkit-tap-highlight-color: transparent;
    font-family: var(--wui-font-family);
    backface-visibility: hidden;
  }
`,vt=l`
  button,
  a {
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    transition: all var(--wui-ease-out-power-1) var(--wui-duration-lg);
    outline: none;
    border: 1px solid transparent;
    column-gap: var(--wui-spacing-3xs);
    background-color: transparent;
    text-decoration: none;
  }

  @media (hover: hover) and (pointer: fine) {
    button:hover:enabled {
      background-color: var(--wui-gray-glass-005);
    }

    button:active:enabled {
      transition: all var(--wui-ease-out-power-2) var(--wui-duration-sm);
      background-color: var(--wui-gray-glass-010);
    }

    button[data-variant='fill']:hover:enabled {
      background-color: var(--wui-color-accent-090);
    }

    button[data-variant='accentBg']:hover:enabled {
      background: var(--wui-accent-glass-015);
    }

    button[data-variant='accentBg']:active:enabled {
      background: var(--wui-accent-glass-020);
    }
  }

  button:disabled {
    cursor: not-allowed;
    background-color: var(--wui-gray-glass-005);
  }

  button[data-variant='shade']:disabled,
  button[data-variant='accent']:disabled,
  button[data-variant='accentBg']:disabled {
    background-color: var(--wui-gray-glass-010);
    color: var(--wui-gray-glass-015);
    filter: grayscale(1);
  }

  button:disabled > wui-wallet-image,
  button:disabled > wui-all-wallets-image,
  button:disabled > wui-network-image,
  button:disabled > wui-image,
  button:disabled > wui-icon-box,
  button:disabled > wui-transaction-visual,
  button:disabled > wui-logo {
    filter: grayscale(1);
  }

  button:focus-visible,
  a:focus-visible {
    border: 1px solid var(--wui-color-accent-100);
    background-color: var(--wui-gray-glass-005);
    -webkit-box-shadow: 0px 0px 0px 4px var(--wui-box-shadow-blue);
    -moz-box-shadow: 0px 0px 0px 4px var(--wui-box-shadow-blue);
    box-shadow: 0px 0px 0px 4px var(--wui-box-shadow-blue);
  }

  button[data-variant='fill']:focus-visible {
    background-color: var(--wui-color-accent-090);
  }

  button[data-variant='fill'] {
    color: var(--wui-color-inverse-100);
    background-color: var(--wui-color-accent-100);
  }

  button[data-variant='fill']:disabled {
    color: var(--wui-gray-glass-015);
    background-color: var(--wui-gray-glass-015);
  }

  button[data-variant='fill']:disabled > wui-icon {
    color: var(--wui-gray-glass-015);
  }

  button[data-variant='shade'] {
    color: var(--wui-color-fg-200);
  }

  button[data-variant='accent'],
  button[data-variant='accentBg'] {
    color: var(--wui-color-accent-100);
  }

  button[data-variant='accentBg'] {
    background: var(--wui-accent-glass-010);
    border: 1px solid var(--wui-accent-glass-010);
  }

  button[data-variant='fullWidth'] {
    width: 100%;
    border-radius: var(--wui-border-radius-xs);
    height: 56px;
    border: none;
    background-color: var(--wui-gray-glass-002);
    color: var(--wui-color-fg-200);
    gap: var(--wui-spacing-xs);
  }

  button:active:enabled {
    background-color: var(--wui-gray-glass-010);
  }

  button[data-variant='fill']:active:enabled {
    background-color: var(--wui-color-accent-080);
    border: 1px solid var(--wui-gray-glass-010);
  }

  input {
    border: none;
    outline: none;
    appearance: none;
  }
`,Et=l`
  .wui-color-inherit {
    color: var(--wui-color-inherit);
  }

  .wui-color-accent-100 {
    color: var(--wui-color-accent-100);
  }

  .wui-color-error-100 {
    color: var(--wui-color-error-100);
  }

  .wui-color-success-100 {
    color: var(--wui-color-success-100);
  }

  .wui-color-inverse-100 {
    color: var(--wui-color-inverse-100);
  }

  .wui-color-inverse-000 {
    color: var(--wui-color-inverse-000);
  }

  .wui-color-fg-100 {
    color: var(--wui-color-fg-100);
  }

  .wui-color-fg-200 {
    color: var(--wui-color-fg-200);
  }

  .wui-color-fg-300 {
    color: var(--wui-color-fg-300);
  }

  .wui-bg-color-inherit {
    background-color: var(--wui-color-inherit);
  }

  .wui-bg-color-blue-100 {
    background-color: var(--wui-color-accent-100);
  }

  .wui-bg-color-error-100 {
    background-color: var(--wui-color-error-100);
  }

  .wui-bg-color-success-100 {
    background-color: var(--wui-color-success-100);
  }

  .wui-bg-color-inverse-100 {
    background-color: var(--wui-color-inverse-100);
  }

  .wui-bg-color-inverse-000 {
    background-color: var(--wui-color-inverse-000);
  }

  .wui-bg-color-fg-100 {
    background-color: var(--wui-color-fg-100);
  }

  .wui-bg-color-fg-200 {
    background-color: var(--wui-color-fg-200);
  }

  .wui-bg-color-fg-300 {
    background-color: var(--wui-color-fg-300);
  }
`;function xt(t){return function(e){return"function"==typeof e?function(t,e){return customElements.get(t)||customElements.define(t,e),e}(t,e):function(t,e){const{kind:r,elements:n}=e;return{kind:r,elements:n,finisher(e){customElements.get(t)||customElements.define(t,e)}}}(t,e)}}const _t=l`
  :host {
    display: block;
    border-radius: clamp(0px, var(--wui-border-radius-l), 44px);
    border: 1px solid var(--wui-gray-glass-005);
    background-color: var(--wui-color-modal-bg);
    overflow: hidden;
  }
`;let At=class extends ht{render(){return G`<slot></slot>`}};At.styles=[bt,_t],At=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o}([xt("wui-card")],At);const kt={attribute:!0,type:String,converter:x,reflect:!1,hasChanged:_},St=(t=kt,e,r)=>{const{kind:n,metadata:i}=r;let s=globalThis.litPropertyMetadata.get(i);if(void 0===s&&globalThis.litPropertyMetadata.set(i,s=new Map),s.set(r.name,t),"accessor"===n){const{name:n}=r;return{set(r){const i=e.get.call(this);e.set.call(this,r),this.requestUpdate(n,i,t)},init(e){return void 0!==e&&this.C(n,void 0,t),e}}}if("setter"===n){const{name:n}=r;return function(r){const i=this[n];e.call(this,r),this.requestUpdate(n,i,t)}}throw Error("Unsupported decorator location: "+n)};function Ct(t){return(e,r)=>"object"==typeof r?St(t,e,r):((t,e,r)=>{const n=e.hasOwnProperty(r);return e.constructor.createProperty(r,n?{...t,wrapped:!0}:t),n?Object.getOwnPropertyDescriptor(e,r):void 0})(t,e,r)}function Mt(t){return Ct({...t,state:!0,attribute:!1})}const It=l`
  :host {
    display: flex;
    aspect-ratio: 1 / 1;
    color: var(--local-color);
    width: var(--local-width);
  }

  svg {
    width: inherit;
    height: inherit;
    object-fit: contain;
    object-position: center;
  }
`,Pt=K`<svg fill="none" viewBox="0 0 24 24">
  <path
    style="fill: var(--wui-color-accent-100);"
    d="M10.2 6.6a3.6 3.6 0 1 1-7.2 0 3.6 3.6 0 0 1 7.2 0ZM21 6.6a3.6 3.6 0 1 1-7.2 0 3.6 3.6 0 0 1 7.2 0ZM10.2 17.4a3.6 3.6 0 1 1-7.2 0 3.6 3.6 0 0 1 7.2 0ZM21 17.4a3.6 3.6 0 1 1-7.2 0 3.6 3.6 0 0 1 7.2 0Z"
  />
</svg>`,Ot=K`
<svg width="36" height="36">
  <path
    d="M28.724 0H7.271A7.269 7.269 0 0 0 0 7.272v21.46A7.268 7.268 0 0 0 7.271 36H28.73A7.272 7.272 0 0 0 36 28.728V7.272A7.275 7.275 0 0 0 28.724 0Z"
    fill="url(#a)"
  />
  <path
    d="m17.845 8.271.729-1.26a1.64 1.64 0 1 1 2.843 1.638l-7.023 12.159h5.08c1.646 0 2.569 1.935 1.853 3.276H6.434a1.632 1.632 0 0 1-1.638-1.638c0-.909.73-1.638 1.638-1.638h4.176l5.345-9.265-1.67-2.898a1.642 1.642 0 0 1 2.844-1.638l.716 1.264Zm-6.317 17.5-1.575 2.732a1.64 1.64 0 1 1-2.844-1.638l1.17-2.025c1.323-.41 2.398-.095 3.249.931Zm13.56-4.954h4.262c.909 0 1.638.729 1.638 1.638 0 .909-.73 1.638-1.638 1.638h-2.367l1.597 2.772c.45.788.185 1.782-.602 2.241a1.642 1.642 0 0 1-2.241-.603c-2.69-4.666-4.711-8.159-6.052-10.485-1.372-2.367-.391-4.743.576-5.549 1.075 1.846 2.682 4.631 4.828 8.348Z"
    fill="#fff"
  />
  <defs>
    <linearGradient id="a" x1="18" y1="0" x2="18" y2="36" gradientUnits="userSpaceOnUse">
      <stop stop-color="#18BFFB" />
      <stop offset="1" stop-color="#2072F3" />
    </linearGradient>
  </defs>
</svg>`,Tt=K`<svg fill="none" viewBox="0 0 40 40">
  <g clip-path="url(#a)">
    <g clip-path="url(#b)">
      <circle cx="20" cy="19.89" r="20" fill="#000" />
      <g clip-path="url(#c)">
        <path
          fill="#fff"
          d="M28.77 23.3c-.69 1.99-2.75 5.52-4.87 5.56-1.4.03-1.86-.84-3.46-.84-1.61 0-2.12.81-3.45.86-2.25.1-5.72-5.1-5.72-9.62 0-4.15 2.9-6.2 5.42-6.25 1.36-.02 2.64.92 3.47.92.83 0 2.38-1.13 4.02-.97.68.03 2.6.28 3.84 2.08-3.27 2.14-2.76 6.61.75 8.25ZM24.2 7.88c-2.47.1-4.49 2.69-4.2 4.84 2.28.17 4.47-2.39 4.2-4.84Z"
        />
      </g>
    </g>
  </g>
  <defs>
    <clipPath id="a"><rect width="40" height="40" fill="#fff" rx="20" /></clipPath>
    <clipPath id="b"><path fill="#fff" d="M0 0h40v40H0z" /></clipPath>
    <clipPath id="c"><path fill="#fff" d="M8 7.89h24v24H8z" /></clipPath>
  </defs>
</svg>`,Nt=K`<svg fill="none" viewBox="0 0 14 15">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M7 1.99a1 1 0 0 1 1 1v7.58l2.46-2.46a1 1 0 0 1 1.41 1.42L7.7 13.69a1 1 0 0 1-1.41 0L2.12 9.53A1 1 0 0 1 3.54 8.1L6 10.57V3a1 1 0 0 1 1-1Z"
    clip-rule="evenodd"
  />
</svg>`,Rt=K`<svg fill="none" viewBox="0 0 14 15">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M13 7.99a1 1 0 0 1-1 1H4.4l2.46 2.46a1 1 0 1 1-1.41 1.41L1.29 8.7a1 1 0 0 1 0-1.41L5.46 3.1a1 1 0 0 1 1.41 1.42L4.41 6.99H12a1 1 0 0 1 1 1Z"
    clip-rule="evenodd"
  />
</svg>`,Bt=K`<svg fill="none" viewBox="0 0 14 15">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M1 7.99a1 1 0 0 1 1-1h7.58L7.12 4.53A1 1 0 1 1 8.54 3.1l4.16 4.17a1 1 0 0 1 0 1.41l-4.16 4.17a1 1 0 1 1-1.42-1.41l2.46-2.46H2a1 1 0 0 1-1-1Z"
    clip-rule="evenodd"
  />
</svg>`,jt=K`<svg fill="none" viewBox="0 0 14 15">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M7 13.99a1 1 0 0 1-1-1V5.4L3.54 7.86a1 1 0 0 1-1.42-1.41L6.3 2.28a1 1 0 0 1 1.41 0l4.17 4.17a1 1 0 1 1-1.41 1.41L8 5.4v7.59a1 1 0 0 1-1 1Z"
    clip-rule="evenodd"
  />
</svg>`,Lt=K`<svg fill="none" viewBox="0 0 20 20">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M4 6.4a1 1 0 0 1-.46.89 6.98 6.98 0 0 0 .38 6.18A7 7 0 0 0 16.46 7.3a1 1 0 0 1-.47-.92 7 7 0 0 0-12 .03Zm-2.02-.5a9 9 0 1 1 16.03 8.2A9 9 0 0 1 1.98 5.9Z"
    clip-rule="evenodd"
  />
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M6.03 8.63c-1.46-.3-2.72-.75-3.6-1.35l-.02-.01-.14-.11a1 1 0 0 1 1.2-1.6l.1.08c.6.4 1.52.74 2.69 1 .16-.99.39-1.88.67-2.65.3-.79.68-1.5 1.15-2.02A2.58 2.58 0 0 1 9.99 1c.8 0 1.45.44 1.92.97.47.52.84 1.23 1.14 2.02.29.77.52 1.66.68 2.64a8 8 0 0 0 2.7-1l.26-.18h.48a1 1 0 0 1 .12 2c-.86.51-2.01.91-3.34 1.18a22.24 22.24 0 0 1-.03 3.19c1.45.29 2.7.73 3.58 1.31a1 1 0 0 1-1.1 1.68c-.6-.4-1.56-.76-2.75-1-.15.8-.36 1.55-.6 2.2-.3.79-.67 1.5-1.14 2.02-.47.53-1.12.97-1.92.97-.8 0-1.45-.44-1.91-.97a6.51 6.51 0 0 1-1.15-2.02c-.24-.65-.44-1.4-.6-2.2-1.18.24-2.13.6-2.73.99a1 1 0 1 1-1.1-1.67c.88-.58 2.12-1.03 3.57-1.31a22.03 22.03 0 0 1-.04-3.2Zm2.2-1.7c.15-.86.34-1.61.58-2.24.24-.65.51-1.12.76-1.4.25-.28.4-.29.42-.29.03 0 .17.01.42.3.25.27.52.74.77 1.4.23.62.43 1.37.57 2.22a19.96 19.96 0 0 1-3.52 0Zm-.18 4.6a20.1 20.1 0 0 1-.03-2.62 21.95 21.95 0 0 0 3.94 0 20.4 20.4 0 0 1-.03 2.63 21.97 21.97 0 0 0-3.88 0Zm.27 2c.13.66.3 1.26.49 1.78.24.65.51 1.12.76 1.4.25.28.4.29.42.29.03 0 .17-.01.42-.3.25-.27.52-.74.77-1.4.19-.5.36-1.1.49-1.78a20.03 20.03 0 0 0-3.35 0Z"
    clip-rule="evenodd"
  />
</svg>`,Ut=K`<svg fill="none" viewBox="0 0 14 15">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M12.04 2.65c.47.3.6.91.3 1.38l-5.78 9a1 1 0 0 1-1.61.1L1.73 9.27A1 1 0 1 1 3.27 8L5.6 10.8l5.05-7.85a1 1 0 0 1 1.38-.3Z"
    clip-rule="evenodd"
  />
</svg>`,Dt=K`<svg fill="none" viewBox="0 0 16 16">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M1.46 4.96a1 1 0 0 1 1.41 0L8 10.09l5.13-5.13a1 1 0 1 1 1.41 1.41l-5.83 5.84a1 1 0 0 1-1.42 0L1.46 6.37a1 1 0 0 1 0-1.41Z"
    clip-rule="evenodd"
  />
</svg>`,$t=K`<svg fill="none" viewBox="0 0 16 16">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M11.04 1.46a1 1 0 0 1 0 1.41L5.91 8l5.13 5.13a1 1 0 1 1-1.41 1.41L3.79 8.71a1 1 0 0 1 0-1.42l5.84-5.83a1 1 0 0 1 1.41 0Z"
    clip-rule="evenodd"
  />
</svg>`,Ft=K`<svg fill="none" viewBox="0 0 16 16">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M4.96 14.54a1 1 0 0 1 0-1.41L10.09 8 4.96 2.87a1 1 0 0 1 1.41-1.41l5.84 5.83a1 1 0 0 1 0 1.42l-5.84 5.83a1 1 0 0 1-1.41 0Z"
    clip-rule="evenodd"
  />
</svg>`,zt=K`<svg fill="none" viewBox="0 0 16 16">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M14.54 11.04a1 1 0 0 1-1.41 0L8 5.92l-5.13 5.12a1 1 0 1 1-1.41-1.41l5.83-5.84a1 1 0 0 1 1.42 0l5.83 5.84a1 1 0 0 1 0 1.41Z"
    clip-rule="evenodd"
  />
</svg>`,Ht=K`<svg width="36" height="36" fill="none">
  <path
    fill="#fff"
    fill-opacity=".05"
    d="M0 14.94c0-5.55 0-8.326 1.182-10.4a9 9 0 0 1 3.359-3.358C6.614 0 9.389 0 14.94 0h6.12c5.55 0 8.326 0 10.4 1.182a9 9 0 0 1 3.358 3.359C36 6.614 36 9.389 36 14.94v6.12c0 5.55 0 8.326-1.182 10.4a9 9 0 0 1-3.359 3.358C29.386 36 26.611 36 21.06 36h-6.12c-5.55 0-8.326 0-10.4-1.182a9 9 0 0 1-3.358-3.359C0 29.386 0 26.611 0 21.06v-6.12Z"
  />
  <path
    stroke="#fff"
    stroke-opacity=".05"
    d="M14.94.5h6.12c2.785 0 4.84 0 6.46.146 1.612.144 2.743.43 3.691.97a8.5 8.5 0 0 1 3.172 3.173c.541.948.826 2.08.971 3.692.145 1.62.146 3.675.146 6.459v6.12c0 2.785 0 4.84-.146 6.46-.145 1.612-.43 2.743-.97 3.691a8.5 8.5 0 0 1-3.173 3.172c-.948.541-2.08.826-3.692.971-1.62.145-3.674.146-6.459.146h-6.12c-2.784 0-4.84 0-6.46-.146-1.612-.145-2.743-.43-3.691-.97a8.5 8.5 0 0 1-3.172-3.173c-.541-.948-.827-2.08-.971-3.692C.5 25.9.5 23.845.5 21.06v-6.12c0-2.784 0-4.84.146-6.46.144-1.612.43-2.743.97-3.691A8.5 8.5 0 0 1 4.79 1.617C5.737 1.076 6.869.79 8.48.646 10.1.5 12.156.5 14.94.5Z"
  />
  <path
    fill="url(#a)"
    d="M17.998 10.8h12.469a14.397 14.397 0 0 0-24.938.001l6.234 10.798.006-.001a7.19 7.19 0 0 1 6.23-10.799Z"
  />
  <path
    fill="url(#b)"
    d="m24.237 21.598-6.234 10.798A14.397 14.397 0 0 0 30.47 10.798H18.002l-.002.006a7.191 7.191 0 0 1 6.237 10.794Z"
  />
  <path
    fill="url(#c)"
    d="M11.765 21.601 5.531 10.803A14.396 14.396 0 0 0 18.001 32.4l6.235-10.798-.004-.004a7.19 7.19 0 0 1-12.466.004Z"
  />
  <path fill="#fff" d="M18 25.2a7.2 7.2 0 1 0 0-14.4 7.2 7.2 0 0 0 0 14.4Z" />
  <path fill="#1A73E8" d="M18 23.7a5.7 5.7 0 1 0 0-11.4 5.7 5.7 0 0 0 0 11.4Z" />
  <defs>
    <linearGradient
      id="a"
      x1="6.294"
      x2="41.1"
      y1="5.995"
      y2="5.995"
      gradientUnits="userSpaceOnUse"
    >
      <stop stop-color="#D93025" />
      <stop offset="1" stop-color="#EA4335" />
    </linearGradient>
    <linearGradient
      id="b"
      x1="20.953"
      x2="37.194"
      y1="32.143"
      y2="2.701"
      gradientUnits="userSpaceOnUse"
    >
      <stop stop-color="#FCC934" />
      <stop offset="1" stop-color="#FBBC04" />
    </linearGradient>
    <linearGradient
      id="c"
      x1="25.873"
      x2="9.632"
      y1="31.2"
      y2="1.759"
      gradientUnits="userSpaceOnUse"
    >
      <stop stop-color="#1E8E3E" />
      <stop offset="1" stop-color="#34A853" />
    </linearGradient>
  </defs>
</svg>`,Wt=K`<svg fill="none" viewBox="0 0 16 16">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M7 2.99a5 5 0 1 0 0 10 5 5 0 0 0 0-10Zm-7 5a7 7 0 1 1 14 0 7 7 0 0 1-14 0Zm7-4a1 1 0 0 1 1 1v2.58l1.85 1.85a1 1 0 0 1-1.41 1.42L6.29 8.69A1 1 0 0 1 6 8v-3a1 1 0 0 1 1-1Z"
    clip-rule="evenodd"
  />
</svg>`,qt=K`<svg fill="none" viewBox="0 0 16 16">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M2.54 2.54a1 1 0 0 1 1.42 0L8 6.6l4.04-4.05a1 1 0 1 1 1.42 1.42L9.4 8l4.05 4.04a1 1 0 0 1-1.42 1.42L8 9.4l-4.04 4.05a1 1 0 0 1-1.42-1.42L6.6 8 2.54 3.96a1 1 0 0 1 0-1.42Z"
    clip-rule="evenodd"
  />
</svg>`,Vt=K`<svg fill="none" viewBox="0 0 20 20">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M10 3a7 7 0 0 0-6.85 8.44l8.29-8.3C10.97 3.06 10.49 3 10 3Zm3.49.93-9.56 9.56c.32.55.71 1.06 1.16 1.5L15 5.1a7.03 7.03 0 0 0-1.5-1.16Zm2.7 2.8-9.46 9.46a7 7 0 0 0 9.46-9.46ZM1.99 5.9A9 9 0 1 1 18 14.09 9 9 0 0 1 1.98 5.91Z"
    clip-rule="evenodd"
  />
</svg>`,Gt=K`<svg fill="none" viewBox="0 0 16 16">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M8 2a6 6 0 1 0 0 12A6 6 0 0 0 8 2ZM0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8Zm10.66-2.65a1 1 0 0 1 .23 1.06L9.83 9.24a1 1 0 0 1-.59.58l-2.83 1.06A1 1 0 0 1 5.13 9.6l1.06-2.82a1 1 0 0 1 .58-.59L9.6 5.12a1 1 0 0 1 1.06.23ZM7.9 7.89l-.13.35.35-.13.12-.35-.34.13Z"
    clip-rule="evenodd"
  />
</svg>`,Kt=K`<svg fill="none" viewBox="0 0 16 16">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M9.5 0h1.67c.68 0 1.26 0 1.73.04.5.05.97.14 1.42.4.52.3.95.72 1.24 1.24.26.45.35.92.4 1.42.04.47.04 1.05.04 1.73V6.5c0 .69 0 1.26-.04 1.74-.05.5-.14.97-.4 1.41-.3.52-.72.95-1.24 1.25-.45.25-.92.35-1.42.4-.43.03-.95.03-1.57.03 0 .62 0 1.14-.04 1.57-.04.5-.14.97-.4 1.42-.29.52-.72.95-1.24 1.24-.44.26-.92.35-1.41.4-.48.04-1.05.04-1.74.04H4.83c-.68 0-1.26 0-1.73-.04-.5-.05-.97-.14-1.42-.4-.52-.3-.95-.72-1.24-1.24a3.39 3.39 0 0 1-.4-1.42A20.9 20.9 0 0 1 0 11.17V9.5c0-.69 0-1.26.04-1.74.05-.5.14-.97.4-1.41.3-.52.72-.95 1.24-1.25.45-.25.92-.35 1.42-.4.43-.03.95-.03 1.57-.03 0-.62 0-1.14.04-1.57.04-.5.14-.97.4-1.42.29-.52.72-.95 1.24-1.24.44-.26.92-.35 1.41-.4A20.9 20.9 0 0 1 9.5 0ZM4.67 6.67c-.63 0-1.06 0-1.4.03-.35.03-.5.09-.6.14-.2.12-.38.3-.5.5-.05.1-.1.24-.14.6C2 8.32 2 8.8 2 9.54v1.59c0 .73 0 1.22.03 1.6.04.35.1.5.15.6.11.2.29.38.5.5.09.05.24.1.6.14.37.03.86.03 1.6.03h1.58c.74 0 1.22 0 1.6-.03.36-.04.5-.1.6-.15.2-.11.38-.29.5-.5.05-.09.1-.24.14-.6.03-.33.03-.76.03-1.39-.6 0-1.13 0-1.57-.04-.5-.04-.97-.14-1.41-.4-.52-.29-.95-.72-1.25-1.24a3.39 3.39 0 0 1-.4-1.41c-.03-.44-.03-.96-.03-1.57Zm3.27-4.64c-.36.04-.5.1-.6.15-.2.11-.38.29-.5.5-.05.09-.1.24-.14.6-.03.37-.03.86-.03 1.6v1.58c0 .74 0 1.22.03 1.6.03.36.09.5.14.6.12.2.3.38.5.5.1.05.24.1.6.14.38.03.86.03 1.6.03h1.59c.73 0 1.22 0 1.6-.03.35-.03.5-.09.6-.14.2-.12.38-.3.5-.5.05-.1.1-.24.14-.6.03-.38.03-.86.03-1.6V4.87c0-.73 0-1.22-.03-1.6a1.46 1.46 0 0 0-.15-.6c-.11-.2-.29-.38-.5-.5-.09-.05-.24-.1-.6-.14-.37-.03-.86-.03-1.6-.03H9.55c-.74 0-1.22 0-1.6.03Z"
    clip-rule="evenodd"
  />
</svg>`,Zt=K` <svg fill="none" viewBox="0 0 13 4">
  <path fill="currentColor" d="M.5 0h12L8.9 3.13a3.76 3.76 0 0 1-4.8 0L.5 0Z" />
</svg>`,Jt=K`<svg fill="none" viewBox="0 0 20 20">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M13.66 2H6.34c-1.07 0-1.96 0-2.68.08-.74.08-1.42.25-2.01.68a4 4 0 0 0-.89.89c-.43.6-.6 1.27-.68 2.01C0 6.38 0 7.26 0 8.34v.89c0 1.07 0 1.96.08 2.68.08.74.25 1.42.68 2.01a4 4 0 0 0 .89.89c.6.43 1.27.6 2.01.68a27 27 0 0 0 2.68.08h7.32a27 27 0 0 0 2.68-.08 4.03 4.03 0 0 0 2.01-.68 4 4 0 0 0 .89-.89c.43-.6.6-1.27.68-2.01.08-.72.08-1.6.08-2.68v-.89c0-1.07 0-1.96-.08-2.68a4.04 4.04 0 0 0-.68-2.01 4 4 0 0 0-.89-.89c-.6-.43-1.27-.6-2.01-.68C15.62 2 14.74 2 13.66 2ZM2.82 4.38c.2-.14.48-.25 1.06-.31C4.48 4 5.25 4 6.4 4h7.2c1.15 0 1.93 0 2.52.07.58.06.86.17 1.06.31a2 2 0 0 1 .44.44c.14.2.25.48.31 1.06.07.6.07 1.37.07 2.52v.77c0 1.15 0 1.93-.07 2.52-.06.58-.17.86-.31 1.06a2 2 0 0 1-.44.44c-.2.14-.48.25-1.06.32-.6.06-1.37.06-2.52.06H6.4c-1.15 0-1.93 0-2.52-.06-.58-.07-.86-.18-1.06-.32a2 2 0 0 1-.44-.44c-.14-.2-.25-.48-.31-1.06C2 11.1 2 10.32 2 9.17V8.4c0-1.15 0-1.93.07-2.52.06-.58.17-.86.31-1.06a2 2 0 0 1 .44-.44Z"
    clip-rule="evenodd"
  />
  <path fill="currentColor" d="M6.14 17.57a1 1 0 1 0 0 2h7.72a1 1 0 1 0 0-2H6.14Z" />
</svg>`,Qt=K`<svg fill="none" viewBox="0 0 16 16">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M6.07 1h.57a1 1 0 0 1 0 2h-.52c-.98 0-1.64 0-2.14.06-.48.05-.7.14-.84.24-.13.1-.25.22-.34.35-.1.14-.2.35-.25.83-.05.5-.05 1.16-.05 2.15v2.74c0 .99 0 1.65.05 2.15.05.48.14.7.25.83.1.14.2.25.34.35.14.1.36.2.84.25.5.05 1.16.05 2.14.05h.52a1 1 0 0 1 0 2h-.57c-.92 0-1.69 0-2.3-.07a3.6 3.6 0 0 1-1.8-.61c-.3-.22-.57-.49-.8-.8a3.6 3.6 0 0 1-.6-1.79C.5 11.11.5 10.35.5 9.43V6.58c0-.92 0-1.7.06-2.31a3.6 3.6 0 0 1 .62-1.8c.22-.3.48-.57.79-.79a3.6 3.6 0 0 1 1.8-.61C4.37 1 5.14 1 6.06 1ZM9.5 3a1 1 0 0 1 1.42 0l4.28 4.3a1 1 0 0 1 0 1.4L10.93 13a1 1 0 0 1-1.42-1.42L12.1 9H6.8a1 1 0 1 1 0-2h5.3L9.51 4.42a1 1 0 0 1 0-1.41Z"
    clip-rule="evenodd"
  />
</svg>`,Yt=K`<svg fill="none" viewBox="0 0 40 40">
  <g clip-path="url(#a)">
    <g clip-path="url(#b)">
      <circle cx="20" cy="19.89" r="20" fill="#5865F2" />
      <path
        fill="#fff"
        fill-rule="evenodd"
        d="M25.71 28.15C30.25 28 32 25.02 32 25.02c0-6.61-2.96-11.98-2.96-11.98-2.96-2.22-5.77-2.15-5.77-2.15l-.29.32c3.5 1.07 5.12 2.61 5.12 2.61a16.75 16.75 0 0 0-10.34-1.93l-.35.04a15.43 15.43 0 0 0-5.88 1.9s1.71-1.63 5.4-2.7l-.2-.24s-2.81-.07-5.77 2.15c0 0-2.96 5.37-2.96 11.98 0 0 1.73 2.98 6.27 3.13l1.37-1.7c-2.6-.79-3.6-2.43-3.6-2.43l.58.35.09.06.08.04.02.01.08.05a17.25 17.25 0 0 0 4.52 1.58 14.4 14.4 0 0 0 8.3-.86c.72-.27 1.52-.66 2.37-1.21 0 0-1.03 1.68-3.72 2.44.61.78 1.35 1.67 1.35 1.67Zm-9.55-9.6c-1.17 0-2.1 1.03-2.1 2.28 0 1.25.95 2.28 2.1 2.28 1.17 0 2.1-1.03 2.1-2.28.01-1.25-.93-2.28-2.1-2.28Zm7.5 0c-1.17 0-2.1 1.03-2.1 2.28 0 1.25.95 2.28 2.1 2.28 1.17 0 2.1-1.03 2.1-2.28 0-1.25-.93-2.28-2.1-2.28Z"
        clip-rule="evenodd"
      />
    </g>
  </g>
  <defs>
    <clipPath id="a"><rect width="40" height="40" fill="#fff" rx="20" /></clipPath>
    <clipPath id="b"><path fill="#fff" d="M0 0h40v40H0z" /></clipPath>
  </defs>
</svg>`,Xt=K`<svg fill="none" viewBox="0 0 16 16">
  <path
    fill="currentColor"
    d="M4.25 7a.63.63 0 0 0-.63.63v3.97c0 .28-.2.51-.47.54l-.75.07a.93.93 0 0 1-.9-.47A7.51 7.51 0 0 1 5.54.92a7.5 7.5 0 0 1 9.54 4.62c.12.35.06.72-.16 1-.74.97-1.68 1.78-2.6 2.44V4.44a.64.64 0 0 0-.63-.64h-1.06c-.35 0-.63.3-.63.64v5.5c0 .23-.12.42-.32.5l-.52.23V6.05c0-.36-.3-.64-.64-.64H7.45c-.35 0-.64.3-.64.64v4.97c0 .25-.17.46-.4.52a5.8 5.8 0 0 0-.45.11v-4c0-.36-.3-.65-.64-.65H4.25ZM14.07 12.4A7.49 7.49 0 0 1 3.6 14.08c4.09-.58 9.14-2.5 11.87-6.6v.03a7.56 7.56 0 0 1-1.41 4.91Z"
  />
</svg>`,te=K`<svg fill="none" viewBox="0 0 14 15">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M6.71 2.99a.57.57 0 0 0-.57.57 1 1 0 0 1-1 1c-.58 0-.96 0-1.24.03-.27.03-.37.07-.42.1a.97.97 0 0 0-.36.35c-.04.08-.09.21-.11.67a2.57 2.57 0 0 1 0 5.13c.02.45.07.6.11.66.09.15.21.28.36.36.07.04.21.1.67.12a2.57 2.57 0 0 1 5.12 0c.46-.03.6-.08.67-.12a.97.97 0 0 0 .36-.36c.03-.04.07-.14.1-.41.02-.29.03-.66.03-1.24a1 1 0 0 1 1-1 .57.57 0 0 0 0-1.15 1 1 0 0 1-1-1c0-.58 0-.95-.03-1.24a1.04 1.04 0 0 0-.1-.42.97.97 0 0 0-.36-.36 1.04 1.04 0 0 0-.42-.1c-.28-.02-.65-.02-1.24-.02a1 1 0 0 1-1-1 .57.57 0 0 0-.57-.57ZM5.15 13.98a1 1 0 0 0 .99-1v-.78a.57.57 0 0 1 1.14 0v.78a1 1 0 0 0 .99 1H8.36a66.26 66.26 0 0 0 .73 0 3.78 3.78 0 0 0 1.84-.38c.46-.26.85-.64 1.1-1.1.23-.4.32-.8.36-1.22.02-.2.03-.4.03-.63a2.57 2.57 0 0 0 0-4.75c0-.23-.01-.44-.03-.63a2.96 2.96 0 0 0-.35-1.22 2.97 2.97 0 0 0-1.1-1.1c-.4-.22-.8-.31-1.22-.35a8.7 8.7 0 0 0-.64-.04 2.57 2.57 0 0 0-4.74 0c-.23 0-.44.02-.63.04-.42.04-.83.13-1.22.35-.46.26-.84.64-1.1 1.1-.33.57-.37 1.2-.39 1.84a21.39 21.39 0 0 0 0 .72v.1a1 1 0 0 0 1 .99h.78a.57.57 0 0 1 0 1.15h-.77a1 1 0 0 0-1 .98v.1a63.87 63.87 0 0 0 0 .73c0 .64.05 1.27.38 1.83.26.47.64.85 1.1 1.11.56.32 1.2.37 1.84.38a20.93 20.93 0 0 0 .72 0h.1Z"
    clip-rule="evenodd"
  />
</svg>`,ee=K`<svg fill="none" viewBox="0 0 14 15">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M3.74 3.99a1 1 0 0 1 1-1H11a1 1 0 0 1 1 1v6.26a1 1 0 0 1-2 0V6.4l-6.3 6.3a1 1 0 0 1-1.4-1.42l6.29-6.3H4.74a1 1 0 0 1-1-1Z"
    clip-rule="evenodd"
  />
</svg>`,re=K`<svg fill="none" viewBox="0 0 40 40">
  <g clip-path="url(#a)">
    <g clip-path="url(#b)">
      <circle cx="20" cy="19.89" r="20" fill="#1877F2" />
      <g clip-path="url(#c)">
        <path
          fill="#fff"
          d="M26 12.38h-2.89c-.92 0-1.61.38-1.61 1.34v1.66H26l-.36 4.5H21.5v12H17v-12h-3v-4.5h3V12.5c0-3.03 1.6-4.62 5.2-4.62H26v4.5Z"
        />
      </g>
    </g>
    <path
      fill="#1877F2"
      d="M40 20a20 20 0 1 0-23.13 19.76V25.78H11.8V20h5.07v-4.4c0-5.02 3-7.79 7.56-7.79 2.19 0 4.48.4 4.48.4v4.91h-2.53c-2.48 0-3.25 1.55-3.25 3.13V20h5.54l-.88 5.78h-4.66v13.98A20 20 0 0 0 40 20Z"
    />
    <path
      fill="#fff"
      d="m27.79 25.78.88-5.78h-5.55v-3.75c0-1.58.78-3.13 3.26-3.13h2.53V8.2s-2.3-.39-4.48-.39c-4.57 0-7.55 2.77-7.55 7.78V20H11.8v5.78h5.07v13.98a20.15 20.15 0 0 0 6.25 0V25.78h4.67Z"
    />
  </g>
  <defs>
    <clipPath id="a"><rect width="40" height="40" fill="#fff" rx="20" /></clipPath>
    <clipPath id="b"><path fill="#fff" d="M0 0h40v40H0z" /></clipPath>
    <clipPath id="c"><path fill="#fff" d="M8 7.89h24v24H8z" /></clipPath>
  </defs>
</svg>`,ne=K`<svg fill="none" viewBox="0 0 16 16">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M0 3a1 1 0 0 1 1-1h14a1 1 0 1 1 0 2H1a1 1 0 0 1-1-1Zm2.63 5.25a1 1 0 0 1 1-1h8.75a1 1 0 1 1 0 2H3.63a1 1 0 0 1-1-1Zm2.62 5.25a1 1 0 0 1 1-1h3.5a1 1 0 0 1 0 2h-3.5a1 1 0 0 1-1-1Z"
    clip-rule="evenodd"
  />
</svg>`,ie=K`<svg fill="none" viewBox="0 0 40 40">
  <g clip-path="url(#a)">
    <g clip-path="url(#b)">
      <circle cx="20" cy="19.89" r="20" fill="#1B1F23" />
      <g clip-path="url(#c)">
        <path
          fill="#fff"
          d="M8 19.89a12 12 0 1 1 15.8 11.38c-.6.12-.8-.26-.8-.57v-3.3c0-1.12-.4-1.85-.82-2.22 2.67-.3 5.48-1.31 5.48-5.92 0-1.31-.47-2.38-1.24-3.22.13-.3.54-1.52-.12-3.18 0 0-1-.32-3.3 1.23a11.54 11.54 0 0 0-6 0c-2.3-1.55-3.3-1.23-3.3-1.23a4.32 4.32 0 0 0-.12 3.18 4.64 4.64 0 0 0-1.24 3.22c0 4.6 2.8 5.63 5.47 5.93-.34.3-.65.83-.76 1.6-.69.31-2.42.84-3.5-1 0 0-.63-1.15-1.83-1.23 0 0-1.18-.02-.09.73 0 0 .8.37 1.34 1.76 0 0 .7 2.14 4.03 1.41v2.24c0 .31-.2.68-.8.57A12 12 0 0 1 8 19.9Z"
        />
      </g>
    </g>
  </g>
  <defs>
    <clipPath id="a"><rect width="40" height="40" fill="#fff" rx="20" /></clipPath>
    <clipPath id="b"><path fill="#fff" d="M0 0h40v40H0z" /></clipPath>
    <clipPath id="c"><path fill="#fff" d="M8 7.89h24v24H8z" /></clipPath>
  </defs>
</svg>`,se=K`<svg fill="none" viewBox="0 0 40 40">
  <g clip-path="url(#a)">
    <g clip-path="url(#b)">
      <circle cx="20" cy="19.89" r="20" fill="#fff" fill-opacity=".05" />
      <g clip-path="url(#c)">
        <path
          fill="#4285F4"
          d="M20 17.7v4.65h6.46a5.53 5.53 0 0 1-2.41 3.61l3.9 3.02c2.26-2.09 3.57-5.17 3.57-8.82 0-.85-.08-1.67-.22-2.46H20Z"
        />
        <path
          fill="#34A853"
          d="m13.27 22.17-.87.67-3.11 2.42A12 12 0 0 0 20 31.9c3.24 0 5.96-1.07 7.94-2.9l-3.9-3.03A7.15 7.15 0 0 1 20 27.12a7.16 7.16 0 0 1-6.72-4.94v-.01Z"
        />
        <path
          fill="#FBBC05"
          d="M9.29 14.5a11.85 11.85 0 0 0 0 10.76l3.99-3.1a7.19 7.19 0 0 1 0-4.55l-4-3.1Z"
        />
        <path
          fill="#EA4335"
          d="M20 12.66c1.77 0 3.34.61 4.6 1.8l3.43-3.44A11.51 11.51 0 0 0 20 7.89c-4.7 0-8.74 2.69-10.71 6.62l3.99 3.1A7.16 7.16 0 0 1 20 12.66Z"
        />
      </g>
    </g>
  </g>
  <defs>
    <clipPath id="a"><rect width="40" height="40" fill="#fff" rx="20" /></clipPath>
    <clipPath id="b"><path fill="#fff" d="M0 0h40v40H0z" /></clipPath>
    <clipPath id="c"><path fill="#fff" d="M8 7.89h24v24H8z" /></clipPath>
  </defs>
</svg>`,oe=K`<svg fill="none" viewBox="0 0 16 16">
  <path
    fill="currentColor"
    d="M8.51 5.66a.83.83 0 0 0-.57-.2.83.83 0 0 0-.52.28.8.8 0 0 0-.25.52 1 1 0 0 1-2 0c0-.75.34-1.43.81-1.91a2.75 2.75 0 0 1 4.78 1.92c0 1.24-.8 1.86-1.25 2.2l-.04.03c-.47.36-.5.43-.5.65a1 1 0 1 1-2 0c0-1.25.8-1.86 1.24-2.2l.04-.04c.47-.36.5-.43.5-.65 0-.3-.1-.49-.24-.6ZM9.12 11.87a1.13 1.13 0 1 1-2.25 0 1.13 1.13 0 0 1 2.25 0Z"
  />
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8Zm8-6a6 6 0 1 0 0 12A6 6 0 0 0 8 2Z"
    clip-rule="evenodd"
  />
</svg>`,ae=K`<svg fill="none" viewBox="0 0 14 15">
  <path
    fill="currentColor"
    d="M6 10.49a1 1 0 1 0 2 0v-2a1 1 0 0 0-2 0v2ZM7 4.49a1 1 0 1 0 0 2 1 1 0 0 0 0-2Z"
  />
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M7 14.99a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm5-7a5 5 0 1 1-10 0 5 5 0 0 1 10 0Z"
    clip-rule="evenodd"
  />
</svg>`,ce=K`<svg fill="none" viewBox="0 0 16 16">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M4.83 1.34h6.34c.68 0 1.26 0 1.73.04.5.05.97.15 1.42.4.52.3.95.72 1.24 1.24.26.45.35.92.4 1.42.04.47.04 1.05.04 1.73v3.71c0 .69 0 1.26-.04 1.74-.05.5-.14.97-.4 1.41-.3.52-.72.95-1.24 1.25-.45.25-.92.35-1.42.4-.47.03-1.05.03-1.73.03H4.83c-.68 0-1.26 0-1.73-.04-.5-.04-.97-.14-1.42-.4-.52-.29-.95-.72-1.24-1.24a3.39 3.39 0 0 1-.4-1.41A20.9 20.9 0 0 1 0 9.88v-3.7c0-.7 0-1.27.04-1.74.05-.5.14-.97.4-1.42.3-.52.72-.95 1.24-1.24.45-.25.92-.35 1.42-.4.47-.04 1.05-.04 1.73-.04ZM3.28 3.38c-.36.03-.51.08-.6.14-.21.11-.39.29-.5.5a.8.8 0 0 0-.08.19l5.16 3.44c.45.3 1.03.3 1.48 0L13.9 4.2a.79.79 0 0 0-.08-.2c-.11-.2-.29-.38-.5-.5-.09-.05-.24-.1-.6-.13-.37-.04-.86-.04-1.6-.04H4.88c-.73 0-1.22 0-1.6.04ZM14 6.54 9.85 9.31a3.33 3.33 0 0 1-3.7 0L2 6.54v3.3c0 .74 0 1.22.03 1.6.04.36.1.5.15.6.11.2.29.38.5.5.09.05.24.1.6.14.37.03.86.03 1.6.03h6.25c.73 0 1.22 0 1.6-.03.35-.03.5-.09.6-.14.2-.12.38-.3.5-.5.05-.1.1-.24.14-.6.03-.38.03-.86.03-1.6v-3.3Z"
    clip-rule="evenodd"
  />
</svg>`,le=K`<svg fill="none" viewBox="0 0 20 20">
  <path fill="currentColor" d="M10.81 5.81a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z" />
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M3 4.75A4.75 4.75 0 0 1 7.75 0h4.5A4.75 4.75 0 0 1 17 4.75v10.5A4.75 4.75 0 0 1 12.25 20h-4.5A4.75 4.75 0 0 1 3 15.25V4.75ZM7.75 2A2.75 2.75 0 0 0 5 4.75v10.5A2.75 2.75 0 0 0 7.75 18h4.5A2.75 2.75 0 0 0 15 15.25V4.75A2.75 2.75 0 0 0 12.25 2h-4.5Z"
    clip-rule="evenodd"
  />
</svg>`,ue=K`<svg fill="none" viewBox="0 0 22 20">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M16.32 13.62a3.14 3.14 0 1 1-.99 1.72l-1.6-.93a3.83 3.83 0 0 1-3.71 1 3.66 3.66 0 0 1-1.74-1l-1.6.94a3.14 3.14 0 1 1-1-1.73l1.6-.94a3.7 3.7 0 0 1 0-2 3.81 3.81 0 0 1 1.8-2.33c.29-.17.6-.3.92-.38V6.1a3.14 3.14 0 1 1 2 0l-.01.02v1.85H12a3.82 3.82 0 0 1 2.33 1.8 3.7 3.7 0 0 1 .39 2.91l1.6.93ZM2.6 16.54a1.14 1.14 0 0 0 1.98-1.14 1.14 1.14 0 0 0-1.98 1.14ZM11 2.01a1.14 1.14 0 1 0 0 2.28 1.14 1.14 0 0 0 0-2.28Zm1.68 10.45c.08-.19.14-.38.16-.58v-.05l.02-.13v-.13a1.92 1.92 0 0 0-.24-.8l-.11-.15a1.89 1.89 0 0 0-.74-.6 1.86 1.86 0 0 0-.77-.17h-.19a1.97 1.97 0 0 0-.89.34 1.98 1.98 0 0 0-.61.74 1.99 1.99 0 0 0-.16.9v.05a1.87 1.87 0 0 0 .24.74l.1.15c.12.16.26.3.42.42l.16.1.13.07.04.02a1.84 1.84 0 0 0 .76.17h.17a2 2 0 0 0 .91-.35 1.78 1.78 0 0 0 .52-.58l.03-.05a.84.84 0 0 0 .05-.11Zm5.15 4.5a1.14 1.14 0 0 0 1.14-1.97 1.13 1.13 0 0 0-1.55.41c-.32.55-.13 1.25.41 1.56Z"
    clip-rule="evenodd"
  />
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M4.63 9.43a1.5 1.5 0 1 0 1.5-2.6 1.5 1.5 0 0 0-1.5 2.6Zm.32-1.55a.5.5 0 0 1 .68-.19.5.5 0 0 1 .18.68.5.5 0 0 1-.68.19.5.5 0 0 1-.18-.68ZM17.94 8.88a1.5 1.5 0 1 1-2.6-1.5 1.5 1.5 0 1 1 2.6 1.5ZM16.9 7.69a.5.5 0 0 0-.68.19.5.5 0 0 0 .18.68.5.5 0 0 0 .68-.19.5.5 0 0 0-.18-.68ZM9.75 17.75a1.5 1.5 0 1 1 2.6 1.5 1.5 1.5 0 1 1-2.6-1.5Zm1.05 1.18a.5.5 0 0 0 .68-.18.5.5 0 0 0-.18-.68.5.5 0 0 0-.68.18.5.5 0 0 0 .18.68Z"
    clip-rule="evenodd"
  />
</svg>`,he=K`<svg fill="none" viewBox="0 0 20 20">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M9.13 1h1.71c1.46 0 2.63 0 3.56.1.97.1 1.8.33 2.53.85a5 5 0 0 1 1.1 1.11c.53.73.75 1.56.86 2.53.1.93.1 2.1.1 3.55v1.72c0 1.45 0 2.62-.1 3.55-.1.97-.33 1.8-.86 2.53a5 5 0 0 1-1.1 1.1c-.73.53-1.56.75-2.53.86-.93.1-2.1.1-3.55.1H9.13c-1.45 0-2.62 0-3.56-.1-.96-.1-1.8-.33-2.52-.85a5 5 0 0 1-1.1-1.11 5.05 5.05 0 0 1-.86-2.53c-.1-.93-.1-2.1-.1-3.55V9.14c0-1.45 0-2.62.1-3.55.1-.97.33-1.8.85-2.53a5 5 0 0 1 1.1-1.1 5.05 5.05 0 0 1 2.53-.86C6.51 1 7.67 1 9.13 1ZM5.79 3.09a3.1 3.1 0 0 0-1.57.48 3 3 0 0 0-.66.67c-.24.32-.4.77-.48 1.56-.1.82-.1 1.88-.1 3.4v1.6c0 1.15 0 2.04.05 2.76l.41-.42c.5-.5.93-.92 1.32-1.24.41-.33.86-.6 1.43-.7a3 3 0 0 1 .94 0c.35.06.66.2.95.37a17.11 17.11 0 0 0 .8.45c.1-.08.2-.2.41-.4l.04-.03a27 27 0 0 1 1.95-1.84 4.03 4.03 0 0 1 1.91-.94 4 4 0 0 1 1.25 0c.73.11 1.33.46 1.91.94l.64.55V9.2c0-1.52 0-2.58-.1-3.4a3.1 3.1 0 0 0-.48-1.56 3 3 0 0 0-.66-.67 3.1 3.1 0 0 0-1.56-.48C13.37 3 12.3 3 10.79 3h-1.6c-1.52 0-2.59 0-3.4.09Zm11.18 10-.04-.05a26.24 26.24 0 0 0-1.83-1.74c-.45-.36-.73-.48-.97-.52a2 2 0 0 0-.63 0c-.24.04-.51.16-.97.52-.46.38-1.01.93-1.83 1.74l-.02.02c-.17.18-.34.34-.49.47a2.04 2.04 0 0 1-1.08.5 1.97 1.97 0 0 1-1.25-.27l-.79-.46-.02-.02a.65.65 0 0 0-.24-.1 1 1 0 0 0-.31 0c-.08.02-.21.06-.49.28-.3.24-.65.59-1.2 1.14l-.56.56-.65.66a3 3 0 0 0 .62.6c.33.24.77.4 1.57.49.81.09 1.88.09 3.4.09h1.6c1.52 0 2.58 0 3.4-.09a3.1 3.1 0 0 0 1.56-.48 3 3 0 0 0 .66-.67c.24-.32.4-.77.49-1.56l.07-1.12Zm-8.02-1.03ZM4.99 7a2 2 0 1 1 4 0 2 2 0 0 1-4 0Z"
    clip-rule="evenodd"
  />
</svg>`,de=K`<svg fill="none" viewBox="0 0 16 16">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M8 0a1 1 0 0 1 1 1v5.38a1 1 0 0 1-2 0V1a1 1 0 0 1 1-1ZM5.26 2.6a1 1 0 0 1-.28 1.39 5.46 5.46 0 1 0 6.04 0 1 1 0 1 1 1.1-1.67 7.46 7.46 0 1 1-8.25 0 1 1 0 0 1 1.4.28Z"
    clip-rule="evenodd"
  />
</svg>`,pe=K` <svg
  width="36"
  height="36"
  fill="none"
>
  <path
    d="M0 8a8 8 0 0 1 8-8h20a8 8 0 0 1 8 8v20a8 8 0 0 1-8 8H8a8 8 0 0 1-8-8V8Z"
    fill="#fff"
    fill-opacity=".05"
  />
  <path
    d="m18.262 17.513-8.944 9.49v.01a2.417 2.417 0 0 0 3.56 1.452l.026-.017 10.061-5.803-4.703-5.132Z"
    fill="#EA4335"
  />
  <path
    d="m27.307 15.9-.008-.008-4.342-2.52-4.896 4.36 4.913 4.912 4.325-2.494a2.42 2.42 0 0 0 .008-4.25Z"
    fill="#FBBC04"
  />
  <path
    d="M9.318 8.997c-.05.202-.084.403-.084.622V26.39c0 .218.025.42.084.621l9.246-9.247-9.246-8.768Z"
    fill="#4285F4"
  />
  <path
    d="m18.33 18 4.627-4.628-10.053-5.828a2.427 2.427 0 0 0-3.586 1.444L18.329 18Z"
    fill="#34A853"
  />
  <path
    d="M8 .5h20A7.5 7.5 0 0 1 35.5 8v20a7.5 7.5 0 0 1-7.5 7.5H8A7.5 7.5 0 0 1 .5 28V8A7.5 7.5 0 0 1 8 .5Z"
    stroke="#fff"
    stroke-opacity=".05"
  />
</svg>`,fe=K`<svg fill="none" viewBox="0 0 20 20">
  <path
    fill="currentColor"
    d="M3 6a3 3 0 0 1 3-3h1a1 1 0 1 0 0-2H6a5 5 0 0 0-5 5v1a1 1 0 0 0 2 0V6ZM13 1a1 1 0 1 0 0 2h1a3 3 0 0 1 3 3v1a1 1 0 1 0 2 0V6a5 5 0 0 0-5-5h-1ZM3 13a1 1 0 1 0-2 0v1a5 5 0 0 0 5 5h1a1 1 0 1 0 0-2H6a3 3 0 0 1-3-3v-1ZM19 13a1 1 0 1 0-2 0v1a3 3 0 0 1-3 3h-1a1 1 0 1 0 0 2h1.01a5 5 0 0 0 5-5v-1ZM5.3 6.36c-.04.2-.04.43-.04.89s0 .7.05.89c.14.52.54.92 1.06 1.06.19.05.42.05.89.05.46 0 .7 0 .88-.05A1.5 1.5 0 0 0 9.2 8.14c.06-.2.06-.43.06-.89s0-.7-.06-.89A1.5 1.5 0 0 0 8.14 5.3c-.19-.05-.42-.05-.88-.05-.47 0-.7 0-.9.05a1.5 1.5 0 0 0-1.05 1.06ZM10.8 6.36c-.04.2-.04.43-.04.89s0 .7.05.89c.14.52.54.92 1.06 1.06.19.05.42.05.89.05.46 0 .7 0 .88-.05a1.5 1.5 0 0 0 1.06-1.06c.06-.2.06-.43.06-.89s0-.7-.06-.89a1.5 1.5 0 0 0-1.06-1.06c-.19-.05-.42-.05-.88-.05-.47 0-.7 0-.9.05a1.5 1.5 0 0 0-1.05 1.06ZM5.26 12.75c0-.46 0-.7.05-.89a1.5 1.5 0 0 1 1.06-1.06c.19-.05.42-.05.89-.05.46 0 .7 0 .88.05.52.14.93.54 1.06 1.06.06.2.06.43.06.89s0 .7-.06.89a1.5 1.5 0 0 1-1.06 1.06c-.19.05-.42.05-.88.05-.47 0-.7 0-.9-.05a1.5 1.5 0 0 1-1.05-1.06c-.05-.2-.05-.43-.05-.89ZM10.8 11.86c-.04.2-.04.43-.04.89s0 .7.05.89c.14.52.54.92 1.06 1.06.19.05.42.05.89.05.46 0 .7 0 .88-.05a1.5 1.5 0 0 0 1.06-1.06c.06-.2.06-.43.06-.89s0-.7-.06-.89a1.5 1.5 0 0 0-1.06-1.06c-.19-.05-.42-.05-.88-.05-.47 0-.7 0-.9.05a1.5 1.5 0 0 0-1.05 1.06Z"
  />
</svg>`,me=K`<svg fill="none" viewBox="0 0 14 16">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M3.94 1.04a1 1 0 0 1 .7 1.23l-.48 1.68a5.85 5.85 0 0 1 8.53 4.32 5.86 5.86 0 0 1-11.4 2.56 1 1 0 0 1 1.9-.57 3.86 3.86 0 1 0 1.83-4.5l1.87.53a1 1 0 0 1-.55 1.92l-4.1-1.15a1 1 0 0 1-.69-1.23l1.16-4.1a1 1 0 0 1 1.23-.7Z"
    clip-rule="evenodd"
  />
</svg>`,ge=K`<svg fill="none" viewBox="0 0 20 20">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M9.36 4.21a5.14 5.14 0 1 0 0 10.29 5.14 5.14 0 0 0 0-10.29ZM1.64 9.36a7.71 7.71 0 1 1 14 4.47l2.52 2.5a1.29 1.29 0 1 1-1.82 1.83l-2.51-2.51A7.71 7.71 0 0 1 1.65 9.36Z"
    clip-rule="evenodd"
  />
</svg>`,ye=K`<svg fill="none" viewBox="0 0 20 20">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M6.76.3a1 1 0 0 1 0 1.4L4.07 4.4h9a1 1 0 1 1 0 2h-9l2.69 2.68a1 1 0 1 1-1.42 1.42L.95 6.09a1 1 0 0 1 0-1.4l4.4-4.4a1 1 0 0 1 1.4 0Zm6.49 9.21a1 1 0 0 1 1.41 0l4.39 4.4a1 1 0 0 1 0 1.4l-4.39 4.4a1 1 0 0 1-1.41-1.42l2.68-2.68h-9a1 1 0 0 1 0-2h9l-2.68-2.68a1 1 0 0 1 0-1.42Z"
    clip-rule="evenodd"
  />
</svg>`,we=K`<svg width="10" height="10" viewBox="0 0 10 10">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M3.77986 0.566631C4.0589 0.845577 4.0589 1.29784 3.77986 1.57678L3.08261 2.2738H6.34184C6.73647 2.2738 7.05637 2.5936 7.05637 2.98808C7.05637 3.38257 6.73647 3.70237 6.34184 3.70237H3.08261L3.77986 4.39938C4.0589 4.67833 4.0589 5.13059 3.77986 5.40954C3.50082 5.68848 3.04841 5.68848 2.76937 5.40954L0.852346 3.49316C0.573306 3.21421 0.573306 2.76195 0.852346 2.48301L2.76937 0.566631C3.04841 0.287685 3.50082 0.287685 3.77986 0.566631ZM6.22 4.59102C6.49904 4.31208 6.95145 4.31208 7.23049 4.59102L9.14751 6.5074C9.42655 6.78634 9.42655 7.23861 9.14751 7.51755L7.23049 9.43393C6.95145 9.71287 6.49904 9.71287 6.22 9.43393C5.94096 9.15498 5.94096 8.70272 6.22 8.42377L6.91725 7.72676L3.65802 7.72676C3.26339 7.72676 2.94349 7.40696 2.94349 7.01247C2.94349 6.61798 3.26339 6.29819 3.65802 6.29819L6.91725 6.29819L6.22 5.60117C5.94096 5.32223 5.94096 4.86997 6.22 4.59102Z"
    clip-rule="evenodd"
  />
</svg>`,be=K`<svg fill="none" viewBox="0 0 14 14">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M3.48 2.18a1 1 0 0 1 1.41 0l2.68 2.68a1 1 0 1 1-1.41 1.42l-.98-.98v4.56a1 1 0 0 1-2 0V5.3l-.97.98A1 1 0 0 1 .79 4.86l2.69-2.68Zm6.34 2.93a1 1 0 0 1 1 1v4.56l.97-.98a1 1 0 1 1 1.42 1.42l-2.69 2.68a1 1 0 0 1-1.41 0l-2.68-2.68a1 1 0 0 1 1.41-1.42l.98.98V6.1a1 1 0 0 1 1-1Z"
    clip-rule="evenodd"
  />
</svg>`,ve=K`<svg fill="none" viewBox="0 0 40 40">
  <g clip-path="url(#a)">
    <g clip-path="url(#b)">
      <circle cx="20" cy="19.89" r="20" fill="#5865F2" />
      <path
        fill="#fff"
        fill-rule="evenodd"
        d="M25.71 28.15C30.25 28 32 25.02 32 25.02c0-6.61-2.96-11.98-2.96-11.98-2.96-2.22-5.77-2.15-5.77-2.15l-.29.32c3.5 1.07 5.12 2.61 5.12 2.61a16.75 16.75 0 0 0-10.34-1.93l-.35.04a15.43 15.43 0 0 0-5.88 1.9s1.71-1.63 5.4-2.7l-.2-.24s-2.81-.07-5.77 2.15c0 0-2.96 5.37-2.96 11.98 0 0 1.73 2.98 6.27 3.13l1.37-1.7c-2.6-.79-3.6-2.43-3.6-2.43l.58.35.09.06.08.04.02.01.08.05a17.25 17.25 0 0 0 4.52 1.58 14.4 14.4 0 0 0 8.3-.86c.72-.27 1.52-.66 2.37-1.21 0 0-1.03 1.68-3.72 2.44.61.78 1.35 1.67 1.35 1.67Zm-9.55-9.6c-1.17 0-2.1 1.03-2.1 2.28 0 1.25.95 2.28 2.1 2.28 1.17 0 2.1-1.03 2.1-2.28.01-1.25-.93-2.28-2.1-2.28Zm7.5 0c-1.17 0-2.1 1.03-2.1 2.28 0 1.25.95 2.28 2.1 2.28 1.17 0 2.1-1.03 2.1-2.28 0-1.25-.93-2.28-2.1-2.28Z"
        clip-rule="evenodd"
      />
    </g>
  </g>
  <defs>
    <clipPath id="a"><rect width="40" height="40" fill="#fff" rx="20" /></clipPath>
    <clipPath id="b"><path fill="#fff" d="M0 0h40v40H0z" /></clipPath>
  </defs>
</svg> `,Ee=K`<svg fill="none" viewBox="0 0 40 40">
  <g clip-path="url(#a)">
    <g clip-path="url(#b)">
      <circle cx="20" cy="19.89" r="20" fill="#5A3E85" />
      <g clip-path="url(#c)">
        <path
          fill="#fff"
          d="M18.22 25.7 20 23.91h3.34l2.1-2.1v-6.68H15.4v8.78h2.82v1.77Zm3.87-8.16h1.25v3.66H22.1v-3.66Zm-3.34 0H20v3.66h-1.25v-3.66ZM20 7.9a12 12 0 1 0 0 24 12 12 0 0 0 0-24Zm6.69 14.56-3.66 3.66h-2.72l-1.77 1.78h-1.88V26.1H13.3v-9.82l.94-2.4H26.7v8.56Z"
        />
      </g>
    </g>
  </g>
  <defs>
    <clipPath id="a"><rect width="40" height="40" fill="#fff" rx="20" /></clipPath>
    <clipPath id="b"><path fill="#fff" d="M0 0h40v40H0z" /></clipPath>
    <clipPath id="c"><path fill="#fff" d="M8 7.89h24v24H8z" /></clipPath>
  </defs>
</svg>`,xe=K`<svg fill="none" viewBox="0 0 40 40">
  <g clip-path="url(#a)">
    <g clip-path="url(#b)">
      <circle cx="20" cy="19.89" r="20" fill="#1D9BF0" />
      <path
        fill="#fff"
        d="M30 13.81c-.74.33-1.53.55-2.36.65.85-.51 1.5-1.32 1.8-2.27-.79.47-1.66.8-2.6 1a4.1 4.1 0 0 0-7 3.73c-3.4-.17-6.42-1.8-8.45-4.28a4.1 4.1 0 0 0 1.27 5.47c-.67-.02-1.3-.2-1.86-.5a4.1 4.1 0 0 0 3.3 4.07c-.58.15-1.21.19-1.86.07a4.1 4.1 0 0 0 3.83 2.85A8.25 8.25 0 0 1 10 26.3a11.62 11.62 0 0 0 6.29 1.84c7.62 0 11.92-6.44 11.66-12.2.8-.59 1.5-1.3 2.05-2.13Z"
      />
    </g>
  </g>
  <defs>
    <clipPath id="a"><rect width="40" height="40" fill="#fff" rx="20" /></clipPath>
    <clipPath id="b"><path fill="#fff" d="M0 0h40v40H0z" /></clipPath>
  </defs>
</svg>`,_e=K`<svg fill="none" viewBox="0 0 16 16">
  <path
    fill="currentColor"
    d="m14.36 4.74.01.42c0 4.34-3.3 9.34-9.34 9.34A9.3 9.3 0 0 1 0 13.03a6.6 6.6 0 0 0 4.86-1.36 3.29 3.29 0 0 1-3.07-2.28c.5.1 1 .07 1.48-.06A3.28 3.28 0 0 1 .64 6.11v-.04c.46.26.97.4 1.49.41A3.29 3.29 0 0 1 1.11 2.1a9.32 9.32 0 0 0 6.77 3.43 3.28 3.28 0 0 1 5.6-3 6.59 6.59 0 0 0 2.08-.8 3.3 3.3 0 0 1-1.45 1.82A6.53 6.53 0 0 0 16 3.04c-.44.66-1 1.23-1.64 1.7Z"
  />
</svg>`,Ae=K`<svg fill="none" viewBox="0 0 28 28">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M18.1 4.76c-.42-.73-1.33-1.01-2.09-.66l-1.42.66c-.37.18-.8.18-1.18 0l-1.4-.65a1.63 1.63 0 0 0-2.1.66l-.84 1.45c-.2.34-.53.59-.92.67l-1.7.35c-.83.17-1.39.94-1.3 1.78l.19 1.56c.04.39-.08.78-.33 1.07l-1.12 1.3c-.52.6-.52 1.5 0 2.11L5 16.38c.25.3.37.68.33 1.06l-.18 1.57c-.1.83.46 1.6 1.28 1.78l1.7.35c.4.08.73.32.93.66l.84 1.43a1.63 1.63 0 0 0 2.09.66l1.41-.66c.37-.17.8-.17 1.18 0l1.43.67c.76.35 1.66.07 2.08-.65l.86-1.45c.2-.34.54-.58.92-.66l1.68-.35A1.63 1.63 0 0 0 22.84 19l-.18-1.57a1.4 1.4 0 0 1 .33-1.06l1.12-1.32c.52-.6.52-1.5 0-2.11l-1.12-1.3a1.4 1.4 0 0 1-.33-1.07l.18-1.57c.1-.83-.46-1.6-1.28-1.77l-1.68-.35a1.4 1.4 0 0 1-.92-.66l-.86-1.47Zm-3.27-3.2a4.43 4.43 0 0 1 5.69 1.78l.54.93 1.07.22a4.43 4.43 0 0 1 3.5 4.84l-.11.96.7.83a4.43 4.43 0 0 1 .02 5.76l-.72.85.1.96a4.43 4.43 0 0 1-3.5 4.84l-1.06.22-.54.92a4.43 4.43 0 0 1-5.68 1.77l-.84-.4-.82.39a4.43 4.43 0 0 1-5.7-1.79l-.51-.89-1.09-.22a4.43 4.43 0 0 1-3.5-4.84l.1-.96-.72-.85a4.43 4.43 0 0 1 .01-5.76l.71-.83-.1-.95a4.43 4.43 0 0 1 3.5-4.84l1.08-.23.53-.9a4.43 4.43 0 0 1 5.7-1.8l.81.38.83-.39ZM18.2 9.4c.65.42.84 1.28.42 1.93l-4.4 6.87a1.4 1.4 0 0 1-2.26.14L9.5 15.39a1.4 1.4 0 0 1 2.15-1.8l1.23 1.48 3.38-5.26a1.4 1.4 0 0 1 1.93-.42Z"
    clip-rule="evenodd"
  />
</svg>`,ke=K`<svg fill="none" viewBox="0 0 14 14">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="m4.1 12.43-.45-.78-.93-.2a1.65 1.65 0 0 1-1.31-1.8l.1-.86-.61-.71a1.65 1.65 0 0 1 0-2.16l.6-.7-.09-.85c-.1-.86.47-1.64 1.3-1.81l.94-.2.45-.78A1.65 1.65 0 0 1 6.23.9l.77.36.78-.36c.77-.36 1.69-.07 2.12.66l.47.8.91.2c.84.17 1.4.95 1.31 1.8l-.1.86.6.7c.54.62.54 1.54.01 2.16l-.6.71.09.86c.1.85-.47 1.63-1.3 1.8l-.92.2-.47.79a1.65 1.65 0 0 1-2.12.66L7 12.74l-.77.36c-.78.35-1.7.07-2.13-.67Zm5.74-6.9a1 1 0 1 0-1.68-1.07L6.32 7.3l-.55-.66a1 1 0 0 0-1.54 1.28l1.43 1.71a1 1 0 0 0 1.61-.1l2.57-4Z"
    clip-rule="evenodd"
  />
</svg>`,Se=K`
  <svg fill="none" viewBox="0 0 48 44">
    <path
      style="fill: var(--wui-color-bg-300);"
      d="M4.56 8.64c-1.23 1.68-1.23 4.08-1.23 8.88v8.96c0 4.8 0 7.2 1.23 8.88.39.55.87 1.02 1.41 1.42C7.65 38 10.05 38 14.85 38h14.3c4.8 0 7.2 0 8.88-1.22a6.4 6.4 0 0 0 1.41-1.42c.83-1.14 1.1-2.6 1.19-4.92a6.4 6.4 0 0 0 5.16-4.65c.21-.81.21-1.8.21-3.79 0-1.98 0-2.98-.22-3.79a6.4 6.4 0 0 0-5.15-4.65c-.1-2.32-.36-3.78-1.19-4.92a6.4 6.4 0 0 0-1.41-1.42C36.35 6 33.95 6 29.15 6h-14.3c-4.8 0-7.2 0-8.88 1.22a6.4 6.4 0 0 0-1.41 1.42Z"
    />
    <path
      style="fill: var(--wui-color-fg-200);"
      fill-rule="evenodd"
      d="M2.27 11.33a6.4 6.4 0 0 1 6.4-6.4h26.66a6.4 6.4 0 0 1 6.4 6.4v1.7a6.4 6.4 0 0 1 5.34 6.3v5.34a6.4 6.4 0 0 1-5.34 6.3v1.7a6.4 6.4 0 0 1-6.4 6.4H8.67a6.4 6.4 0 0 1-6.4-6.4V11.33ZM39.6 31.07h-6.93a9.07 9.07 0 1 1 0-18.14h6.93v-1.6a4.27 4.27 0 0 0-4.27-4.26H8.67a4.27 4.27 0 0 0-4.27 4.26v21.34a4.27 4.27 0 0 0 4.27 4.26h26.66a4.27 4.27 0 0 0 4.27-4.26v-1.6Zm-6.93-16a6.93 6.93 0 0 0 0 13.86h8a4.27 4.27 0 0 0 4.26-4.26v-5.34a4.27 4.27 0 0 0-4.26-4.26h-8Z"
      clip-rule="evenodd"
    />
  </svg>
`;var Ce=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};const Me={allWallets:Pt,appStore:Ot,chromeStore:Ht,apple:Tt,arrowBottom:Nt,arrowLeft:Rt,arrowRight:Bt,arrowTop:jt,browser:Lt,checkmark:Ut,chevronBottom:Dt,chevronLeft:$t,chevronRight:Ft,chevronTop:zt,clock:Wt,close:qt,compass:Gt,coinPlaceholder:Vt,copy:Kt,cursor:Zt,desktop:Jt,disconnect:Qt,discord:Yt,etherscan:Xt,extension:te,externalLink:ee,facebook:re,filters:ne,github:ie,google:se,helpCircle:oe,infoCircle:ae,mail:ce,mobile:le,networkPlaceholder:ue,nftPlaceholder:he,off:de,playStore:pe,qrCode:fe,refresh:me,search:ge,swapHorizontal:ye,swapHorizontalBold:we,swapVertical:be,telegram:ve,twitch:Ee,twitter:xe,twitterIcon:_e,verify:Ae,verifyFilled:ke,wallet:K`<svg fill="none" viewBox="0 0 20 20">
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M0 5.5c0-1.8 1.46-3.25 3.25-3.25H14.5c1.8 0 3.25 1.46 3.25 3.25v.28A3.25 3.25 0 0 1 20 8.88v2.24c0 1.45-.94 2.68-2.25 3.1v.28c0 1.8-1.46 3.25-3.25 3.25H3.25A3.25 3.25 0 0 1 0 14.5v-9Zm15.75 8.88h-2.38a4.38 4.38 0 0 1 0-8.76h2.38V5.5c0-.69-.56-1.25-1.25-1.25H3.25C2.56 4.25 2 4.81 2 5.5v9c0 .69.56 1.25 1.25 1.25H14.5c.69 0 1.25-.56 1.25-1.25v-.13Zm-2.38-6.76a2.37 2.37 0 1 0 0 4.75h3.38c.69 0 1.25-.55 1.25-1.24V8.87c0-.69-.56-1.24-1.25-1.24h-3.38Z"
    clip-rule="evenodd"
  />
</svg>`,walletConnect:K`<svg fill="none" viewBox="0 0 96 67">
  <path
    fill="currentColor"
    d="M25.32 18.8a32.56 32.56 0 0 1 45.36 0l1.5 1.47c.63.62.63 1.61 0 2.22l-5.15 5.05c-.31.3-.82.3-1.14 0l-2.07-2.03a22.71 22.71 0 0 0-31.64 0l-2.22 2.18c-.31.3-.82.3-1.14 0l-5.15-5.05a1.55 1.55 0 0 1 0-2.22l1.65-1.62Zm56.02 10.44 4.59 4.5c.63.6.63 1.6 0 2.21l-20.7 20.26c-.62.61-1.63.61-2.26 0L48.28 41.83a.4.4 0 0 0-.56 0L33.03 56.21c-.63.61-1.64.61-2.27 0L10.07 35.95a1.55 1.55 0 0 1 0-2.22l4.59-4.5a1.63 1.63 0 0 1 2.27 0L31.6 43.63a.4.4 0 0 0 .57 0l14.69-14.38a1.63 1.63 0 0 1 2.26 0l14.69 14.38a.4.4 0 0 0 .57 0l14.68-14.38a1.63 1.63 0 0 1 2.27 0Z"
  />
  <path
    stroke="#000"
    stroke-opacity=".1"
    d="M25.67 19.15a32.06 32.06 0 0 1 44.66 0l1.5 1.48c.43.42.43 1.09 0 1.5l-5.15 5.05a.31.31 0 0 1-.44 0l-2.07-2.03a23.21 23.21 0 0 0-32.34 0l-2.22 2.18a.31.31 0 0 1-.44 0l-5.15-5.05a1.05 1.05 0 0 1 0-1.5l1.65-1.63ZM81 29.6l4.6 4.5c.42.41.42 1.09 0 1.5l-20.7 20.26c-.43.43-1.14.43-1.57 0L48.63 41.47a.9.9 0 0 0-1.26 0L32.68 55.85c-.43.43-1.14.43-1.57 0L10.42 35.6a1.05 1.05 0 0 1 0-1.5l4.59-4.5a1.13 1.13 0 0 1 1.57 0l14.68 14.38a.9.9 0 0 0 1.27 0l-.35-.35.35.35L47.22 29.6a1.13 1.13 0 0 1 1.56 0l14.7 14.38a.9.9 0 0 0 1.26 0L79.42 29.6a1.13 1.13 0 0 1 1.57 0Z"
  />
</svg>`,walletPlaceholder:Se,warningCircle:K`<svg fill="none" viewBox="0 0 20 20">
  <path
    fill="currentColor"
    d="M11 6.67a1 1 0 1 0-2 0v2.66a1 1 0 0 0 2 0V6.67ZM10 14.5a1.25 1.25 0 1 0 0-2.5 1.25 1.25 0 0 0 0 2.5Z"
  />
  <path
    fill="currentColor"
    fill-rule="evenodd"
    d="M10 1a9 9 0 1 0 0 18 9 9 0 0 0 0-18Zm-7 9a7 7 0 1 1 14 0 7 7 0 0 1-14 0Z"
    clip-rule="evenodd"
  />
</svg>`};let Ie=class extends ht{constructor(){super(...arguments),this.size="md",this.name="copy",this.color="fg-300"}render(){return this.style.cssText=`\n      --local-color: var(--wui-color-${this.color});\n      --local-width: var(--wui-icon-size-${this.size});\n    `,G`${Me[this.name]}`}};Ie.styles=[bt,Et,It],Ce([Ct()],Ie.prototype,"size",void 0),Ce([Ct()],Ie.prototype,"name",void 0),Ce([Ct()],Ie.prototype,"color",void 0),Ie=Ce([xt("wui-icon")],Ie);const Pe=l`
  :host {
    display: block;
    width: 100%;
    height: 100%;
  }

  img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center center;
    border-radius: inherit;
  }
`;var Oe=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Te=class extends ht{constructor(){super(...arguments),this.src="./path/to/image.jpg",this.alt="Image"}render(){return G`<img src=${this.src} alt=${this.alt} />`}};Te.styles=[bt,Et,Pe],Oe([Ct()],Te.prototype,"src",void 0),Oe([Ct()],Te.prototype,"alt",void 0),Te=Oe([xt("wui-image")],Te);const Ne=l`
  :host {
    display: block;
    width: var(--wui-box-size-lg);
    height: var(--wui-box-size-lg);
  }

  svg {
    width: var(--wui-box-size-lg);
    height: var(--wui-box-size-lg);
    fill: none;
    stroke: transparent;
    stroke-linecap: round;
    transition: all var(--wui-ease-in-power-3) var(--wui-duration-lg);
  }

  use {
    stroke: var(--wui-color-accent-100);
    stroke-width: 2px;
    stroke-dasharray: 54, 118;
    stroke-dashoffset: 172;
    animation: dash 1s linear infinite;
  }

  @keyframes dash {
    to {
      stroke-dashoffset: 0px;
    }
  }
`;let Re=class extends ht{render(){return G`
      <svg viewBox="0 0 54 59">
        <path
          id="wui-loader-path"
          d="M17.22 5.295c3.877-2.277 5.737-3.363 7.72-3.726a11.44 11.44 0 0 1 4.12 0c1.983.363 3.844 1.45 7.72 3.726l6.065 3.562c3.876 2.276 5.731 3.372 7.032 4.938a11.896 11.896 0 0 1 2.06 3.63c.683 1.928.688 4.11.688 8.663v7.124c0 4.553-.005 6.735-.688 8.664a11.896 11.896 0 0 1-2.06 3.63c-1.3 1.565-3.156 2.66-7.032 4.937l-6.065 3.563c-3.877 2.276-5.737 3.362-7.72 3.725a11.46 11.46 0 0 1-4.12 0c-1.983-.363-3.844-1.449-7.72-3.726l-6.065-3.562c-3.876-2.276-5.731-3.372-7.032-4.938a11.885 11.885 0 0 1-2.06-3.63c-.682-1.928-.688-4.11-.688-8.663v-7.124c0-4.553.006-6.735.688-8.664a11.885 11.885 0 0 1 2.06-3.63c1.3-1.565 3.156-2.66 7.032-4.937l6.065-3.562Z"
        />
        <use xlink:href="#wui-loader-path"></use>
      </svg>
    `}};Re.styles=[bt,Ne],Re=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o}([xt("wui-loading-hexagon")],Re);const Be=l`
  :host {
    display: flex;
  }

  :host([data-size='sm']) > svg {
    width: 12px;
    height: 12px;
  }

  :host([data-size='md']) > svg {
    width: 16px;
    height: 16px;
  }

  :host([data-size='lg']) > svg {
    width: 24px;
    height: 24px;
  }

  :host([data-size='xl']) > svg {
    width: 32px;
    height: 32px;
  }

  svg {
    animation: rotate 2s linear infinite;
    transition: all var(--wui-ease-in-power-3) var(--wui-duration-lg);
  }

  circle {
    fill: none;
    stroke: var(--local-color);
    stroke-width: 4px;
    stroke-dasharray: 1, 124;
    stroke-dashoffset: 0;
    stroke-linecap: round;
    animation: dash 1.5s ease-in-out infinite;
  }

  :host([data-size='md']) > svg > circle {
    stroke-width: 6px;
  }

  :host([data-size='sm']) > svg > circle {
    stroke-width: 8px;
  }

  @keyframes rotate {
    100% {
      transform: rotate(360deg);
    }
  }

  @keyframes dash {
    0% {
      stroke-dasharray: 1, 124;
      stroke-dashoffset: 0;
    }

    50% {
      stroke-dasharray: 90, 124;
      stroke-dashoffset: -35;
    }

    100% {
      stroke-dashoffset: -125;
    }
  }
`;var je=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Le=class extends ht{constructor(){super(...arguments),this.color="accent-100",this.size="lg"}render(){return this.style.cssText=`--local-color: var(--wui-color-${this.color});`,this.dataset.size=this.size,G`<svg viewBox="25 25 50 50">
      <circle r="20" cy="50" cx="50"></circle>
    </svg>`}};Le.styles=[bt,Be],je([Ct()],Le.prototype,"color",void 0),je([Ct()],Le.prototype,"size",void 0),Le=je([xt("wui-loading-spinner")],Le);const Ue=l`
  :host {
    display: block;
    width: var(--wui-box-size-md);
    height: var(--wui-box-size-md);
  }

  svg {
    width: var(--wui-box-size-md);
    height: var(--wui-box-size-md);
    transition: all var(--wui-ease-in-power-3) var(--wui-duration-lg);
  }

  rect {
    fill: none;
    stroke: var(--wui-color-accent-100);
    stroke-width: 4px;
    stroke-linecap: round;
    animation: dash 1s linear infinite;
  }

  @keyframes dash {
    to {
      stroke-dashoffset: 0px;
    }
  }
`;var De=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let $e=class extends ht{constructor(){super(...arguments),this.radius=36}render(){return this.svgLoaderTemplate()}svgLoaderTemplate(){const t=this.radius>50?50:this.radius,e=36-t;return G`
      <svg viewBox="0 0 110 110" width="110" height="110">
        <rect
          x="2"
          y="2"
          width="106"
          height="106"
          rx=${t}
          stroke-dasharray="${116+e} ${245+e}"
          stroke-dashoffset=${360+1.75*e}
        />
      </svg>
    `}};$e.styles=[bt,Ue],De([Ct({type:Number})],$e.prototype,"radius",void 0),$e=De([xt("wui-loading-thumbnail")],$e);const Fe=l`
  :host {
    display: block;
    box-shadow: inset 0 0 0 1px var(--wui-gray-glass-005);
    background: linear-gradient(
      120deg,
      var(--wui-color-bg-200) 5%,
      var(--wui-color-bg-200) 48%,
      var(--wui-color-bg-300) 55%,
      var(--wui-color-bg-300) 60%,
      var(--wui-color-bg-300) calc(60% + 10px),
      var(--wui-color-bg-200) calc(60% + 12px),
      var(--wui-color-bg-200) 100%
    );
    background-size: 250%;
    animation: shimmer 3s linear infinite reverse;
  }

  @keyframes shimmer {
    from {
      background-position: -250% 0;
    }
    to {
      background-position: 250% 0;
    }
  }
`;var ze=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let He=class extends ht{constructor(){super(...arguments),this.width="",this.height="",this.borderRadius="m"}render(){return this.style.cssText=`\n      width: ${this.width};\n      height: ${this.height};\n      border-radius: clamp(0px,var(--wui-border-radius-${this.borderRadius}), 40px);\n    `,G`<slot></slot>`}};He.styles=[Fe],ze([Ct()],He.prototype,"width",void 0),ze([Ct()],He.prototype,"height",void 0),ze([Ct()],He.prototype,"borderRadius",void 0),He=ze([xt("wui-shimmer")],He);const We=t=>(...e)=>({_$litDirective$:t,values:e});class qe{constructor(t){}get _$AU(){return this._$AM._$AU}_$AT(t,e,r){this._$Ct=t,this._$AM=e,this._$Ci=r}_$AS(t,e){return this.update(t,e)}update(t,e){return this.render(...e)}}const Ve=We(class extends qe{constructor(t){if(super(t),1!==t.type||"class"!==t.name||t.strings?.length>2)throw Error("`classMap()` can only be used in the `class` attribute and must be the only part in the attribute.")}render(t){return" "+Object.keys(t).filter((e=>t[e])).join(" ")+" "}update(t,[e]){if(void 0===this.it){this.it=new Set,void 0!==t.strings&&(this.st=new Set(t.strings.join(" ").split(/\s/).filter((t=>""!==t))));for(const t in e)e[t]&&!this.st?.has(t)&&this.it.add(t);return this.render(e)}const r=t.element.classList;for(const t of this.it)t in e||(r.remove(t),this.it.delete(t));for(const t in e){const n=!!e[t];n===this.it.has(t)||this.st?.has(t)||(n?(r.add(t),this.it.add(t)):(r.remove(t),this.it.delete(t)))}return Z}}),Ge=l`
  :host {
    display: flex !important;
  }

  slot {
    display: inline-block;
    font-style: normal;
    font-family: var(--wui-font-family);
    font-feature-settings:
      'tnum' on,
      'lnum' on,
      'case' on;
    line-height: 130%;
    font-weight: var(--wui-font-weight-regular);
    overflow: inherit;
    text-overflow: inherit;
    text-align: var(--local-align);
    color: var(--local-color);
  }

  .wui-font-large-500,
  .wui-font-large-600,
  .wui-font-large-700 {
    font-size: var(--wui-font-size-large);
    letter-spacing: var(--wui-letter-spacing-large);
  }

  .wui-font-paragraph-500,
  .wui-font-paragraph-600,
  .wui-font-paragraph-700 {
    font-size: var(--wui-font-size-paragraph);
    letter-spacing: var(--wui-letter-spacing-paragraph);
  }

  .wui-font-small-400,
  .wui-font-small-500,
  .wui-font-small-600 {
    font-size: var(--wui-font-size-small);
    letter-spacing: var(--wui-letter-spacing-small);
  }

  .wui-font-tiny-500,
  .wui-font-tiny-600 {
    font-size: var(--wui-font-size-tiny);
    letter-spacing: var(--wui-letter-spacing-tiny);
  }

  .wui-font-micro-700,
  .wui-font-micro-600 {
    font-size: var(--wui-font-size-micro);
    letter-spacing: var(--wui-letter-spacing-micro);
    text-transform: uppercase;
  }

  .wui-font-small-400,
  .wui-font-paragraph-400 {
    font-weight: var(--wui-font-weight-light);
  }

  .wui-font-large-700,
  .wui-font-paragraph-700,
  .wui-font-micro-700 {
    font-weight: var(--wui-font-weight-bold);
  }

  .wui-font-large-600,
  .wui-font-paragraph-600,
  .wui-font-small-600,
  .wui-font-tiny-600,
  .wui-font-micro-600 {
    font-weight: var(--wui-font-weight-medium);
  }
`;var Ke=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Ze=class extends ht{constructor(){super(...arguments),this.variant="paragraph-500",this.color="fg-300",this.align="left"}render(){const t={[`wui-font-${this.variant}`]:!0,[`wui-color-${this.color}`]:!0};return this.style.cssText=`\n      --local-align: ${this.align};\n      --local-color: var(--wui-color-${this.color});\n    `,G`<slot class=${Ve(t)}></slot>`}};Ze.styles=[bt,Ge],Ke([Ct()],Ze.prototype,"variant",void 0),Ke([Ct()],Ze.prototype,"color",void 0),Ke([Ct()],Ze.prototype,"align",void 0),Ze=Ke([xt("wui-text")],Ze);const Je=K`<svg fill="none" viewBox="0 0 60 60">
  <rect width="60" height="60" fill="#1DC956" rx="30" />
  <circle cx="30" cy="30" r="3" fill="#fff" />
  <path
    fill="#2BEE6C"
    stroke="#fff"
    stroke-width="2"
    d="m45.32 17.9-.88-.42.88.42.02-.05c.1-.2.21-.44.26-.7l-.82-.15.82.16a2 2 0 0 0-.24-1.4c-.13-.23-.32-.42-.47-.57a8.42 8.42 0 0 1-.04-.04l-.04-.04a2.9 2.9 0 0 0-.56-.47l-.51.86.5-.86a2 2 0 0 0-1.4-.24c-.26.05-.5.16-.69.26l-.05.02-15.05 7.25-.1.05c-1.14.55-1.85.89-2.46 1.37a7 7 0 0 0-1.13 1.14c-.5.6-.83 1.32-1.38 2.45l-.05.11-7.25 15.05-.02.05c-.1.2-.21.43-.26.69a2 2 0 0 0 .24 1.4l.85-.5-.85.5c.13.23.32.42.47.57l.04.04.04.04c.15.15.34.34.56.47a2 2 0 0 0 1.41.24l-.2-.98.2.98c.25-.05.5-.17.69-.26l.05-.02-.42-.87.42.87 15.05-7.25.1-.05c1.14-.55 1.85-.89 2.46-1.38a7 7 0 0 0 1.13-1.13 12.87 12.87 0 0 0 1.43-2.56l7.25-15.05Z"
  />
  <path
    fill="#1DC956"
    d="M33.38 32.72 30.7 29.3 15.86 44.14l.2.2a1 1 0 0 0 1.14.2l15.1-7.27a3 3 0 0 0 1.08-4.55Z"
  />
  <path
    fill="#86F999"
    d="m26.62 27.28 2.67 3.43 14.85-14.85-.2-.2a1 1 0 0 0-1.14-.2l-15.1 7.27a3 3 0 0 0-1.08 4.55Z"
  />
  <circle cx="30" cy="30" r="3" fill="#fff" transform="rotate(45 30 30)" />
  <rect width="59" height="59" x=".5" y=".5" stroke="#062B2B" stroke-opacity=".1" rx="29.5" />
</svg> `,Qe=K`<svg viewBox="0 0 60 60" fill="none">
  <g clip-path="url(#clip0_7734_50402)">
    <path
      d="M0 24.9C0 15.6485 0 11.0228 1.97053 7.56812C3.3015 5.23468 5.23468 3.3015 7.56812 1.97053C11.0228 0 15.6485 0 24.9 0H35.1C44.3514 0 48.9772 0 52.4319 1.97053C54.7653 3.3015 56.6985 5.23468 58.0295 7.56812C60 11.0228 60 15.6485 60 24.9V35.1C60 44.3514 60 48.9772 58.0295 52.4319C56.6985 54.7653 54.7653 56.6985 52.4319 58.0295C48.9772 60 44.3514 60 35.1 60H24.9C15.6485 60 11.0228 60 7.56812 58.0295C5.23468 56.6985 3.3015 54.7653 1.97053 52.4319C0 48.9772 0 44.3514 0 35.1V24.9Z"
      fill="#EB8B47"
    />
    <path
      d="M0.5 24.9C0.5 20.2652 0.50047 16.8221 0.744315 14.105C0.987552 11.3946 1.46987 9.45504 2.40484 7.81585C3.69145 5.56019 5.56019 3.69145 7.81585 2.40484C9.45504 1.46987 11.3946 0.987552 14.105 0.744315C16.8221 0.50047 20.2652 0.5 24.9 0.5H35.1C39.7348 0.5 43.1779 0.50047 45.895 0.744315C48.6054 0.987552 50.545 1.46987 52.1841 2.40484C54.4398 3.69145 56.3086 5.56019 57.5952 7.81585C58.5301 9.45504 59.0124 11.3946 59.2557 14.105C59.4995 16.8221 59.5 20.2652 59.5 24.9V35.1C59.5 39.7348 59.4995 43.1779 59.2557 45.895C59.0124 48.6054 58.5301 50.545 57.5952 52.1841C56.3086 54.4398 54.4398 56.3086 52.1841 57.5952C50.545 58.5301 48.6054 59.0124 45.895 59.2557C43.1779 59.4995 39.7348 59.5 35.1 59.5H24.9C20.2652 59.5 16.8221 59.4995 14.105 59.2557C11.3946 59.0124 9.45504 58.5301 7.81585 57.5952C5.56019 56.3086 3.69145 54.4398 2.40484 52.1841C1.46987 50.545 0.987552 48.6054 0.744315 45.895C0.50047 43.1779 0.5 39.7348 0.5 35.1V24.9Z"
      stroke="#062B2B"
      stroke-opacity="0.1"
    />
    <path
      d="M19 52C24.5228 52 29 47.5228 29 42C29 36.4772 24.5228 32 19 32C13.4772 32 9 36.4772 9 42C9 47.5228 13.4772 52 19 52Z"
      fill="#FF974C"
      stroke="white"
      stroke-width="2"
    />
    <path
      fill-rule="evenodd"
      clip-rule="evenodd"
      d="M42.8437 8.3264C42.4507 7.70891 41.5493 7.70891 41.1564 8.32641L28.978 27.4638C28.5544 28.1295 29.0326 29.0007 29.8217 29.0007H54.1783C54.9674 29.0007 55.4456 28.1295 55.022 27.4638L42.8437 8.3264Z"
      fill="white"
    />
    <path
      fill-rule="evenodd"
      clip-rule="evenodd"
      d="M42.3348 11.6456C42.659 11.7608 42.9061 12.1492 43.4005 12.926L50.7332 24.4488C51.2952 25.332 51.5763 25.7737 51.5254 26.1382C51.4915 26.3808 51.3698 26.6026 51.1833 26.7614C50.9031 27 50.3796 27 49.3327 27H34.6673C33.6204 27 33.0969 27 32.8167 26.7614C32.6302 26.6026 32.5085 26.3808 32.4746 26.1382C32.4237 25.7737 32.7048 25.332 33.2669 24.4488L40.5995 12.926C41.0939 12.1492 41.341 11.7608 41.6652 11.6456C41.8818 11.5687 42.1182 11.5687 42.3348 11.6456ZM35.0001 26.999C38.8661 26.999 42.0001 23.865 42.0001 19.999C42.0001 23.865 45.1341 26.999 49.0001 26.999H35.0001Z"
      fill="#FF974C"
    />
    <path
      d="M10.1061 9.35712C9.9973 9.67775 9.99867 10.0388 9.99978 10.3323C9.99989 10.3611 10 10.3893 10 10.4167V25.5833C10 25.6107 9.99989 25.6389 9.99978 25.6677C9.99867 25.9612 9.9973 26.3222 10.1061 26.6429C10.306 27.2317 10.7683 27.694 11.3571 27.8939C11.6777 28.0027 12.0388 28.0013 12.3323 28.0002C12.3611 28.0001 12.3893 28 12.4167 28H19C24.5228 28 29 23.5228 29 18C29 12.4772 24.5228 8 19 8H12.4167C12.3893 8 12.3611 7.99989 12.3323 7.99978C12.0388 7.99867 11.6778 7.9973 11.3571 8.10614C10.7683 8.306 10.306 8.76834 10.1061 9.35712Z"
      fill="#FF974C"
      stroke="white"
      stroke-width="2"
    />
    <circle cx="19" cy="18" r="4" fill="#EB8B47" stroke="white" stroke-width="2" />
    <circle cx="19" cy="42" r="4" fill="#EB8B47" stroke="white" stroke-width="2" />
  </g>
  <defs>
    <clipPath id="clip0_7734_50402">
      <rect width="60" height="60" fill="white" />
    </clipPath>
  </defs>
</svg> `,Ye=K`<svg fill="none" viewBox="0 0 60 60">
  <g clip-path="url(#a)">
    <path
      fill="#1DC956"
      d="M0 25.01c0-9.25 0-13.88 1.97-17.33a15 15 0 0 1 5.6-5.6C11.02.11 15.65.11 24.9.11h10.2c9.25 0 13.88 0 17.33 1.97a15 15 0 0 1 5.6 5.6C60 11.13 60 15.76 60 25v10.2c0 9.25 0 13.88-1.97 17.33a15 15 0 0 1-5.6 5.6c-3.45 1.97-8.08 1.97-17.33 1.97H24.9c-9.25 0-13.88 0-17.33-1.97a15 15 0 0 1-5.6-5.6C0 49.1 0 44.46 0 35.21v-10.2Z"
    />
    <path
      fill="#2BEE6C"
      d="M16.1 60c-3.82-.18-6.4-.64-8.53-1.86a15 15 0 0 1-5.6-5.6C.55 50.06.16 46.97.04 41.98L4.2 40.6a4 4 0 0 0 2.48-2.39l4.65-12.4a2 2 0 0 1 2.5-1.2l2.53.84a2 2 0 0 0 2.43-1l2.96-5.94a2 2 0 0 1 3.7.32l3.78 12.58a2 2 0 0 0 3.03 1.09l3.34-2.23a2 2 0 0 0 .65-.7l5.3-9.72a2 2 0 0 1 1.42-1.01l4.14-.69a2 2 0 0 1 1.6.44l3.9 3.24a2 2 0 0 0 2.7-.12l4.62-4.63c.08 2.2.08 4.8.08 7.93v10.2c0 9.25 0 13.88-1.97 17.33a15 15 0 0 1-5.6 5.6c-2.13 1.22-4.7 1.68-8.54 1.86H16.11Z"
    />
    <path
      fill="#fff"
      d="m.07 43.03-.05-2.1 3.85-1.28a3 3 0 0 0 1.86-1.79l4.66-12.4a3 3 0 0 1 3.75-1.8l2.53.84a1 1 0 0 0 1.21-.5l2.97-5.94a3 3 0 0 1 5.56.48l3.77 12.58a1 1 0 0 0 1.51.55l3.34-2.23a1 1 0 0 0 .33-.35l5.3-9.71a3 3 0 0 1 2.14-1.53l4.13-.69a3 3 0 0 1 2.41.66l3.9 3.24a1 1 0 0 0 1.34-.06l5.28-5.28c.05.85.08 1.75.1 2.73L56 22.41a3 3 0 0 1-4.04.19l-3.9-3.25a1 1 0 0 0-.8-.21l-4.13.69a1 1 0 0 0-.72.5l-5.3 9.72a3 3 0 0 1-.97 1.05l-3.34 2.23a3 3 0 0 1-4.53-1.63l-3.78-12.58a1 1 0 0 0-1.85-.16l-2.97 5.94a3 3 0 0 1-3.63 1.5l-2.53-.84a1 1 0 0 0-1.25.6l-4.65 12.4a5 5 0 0 1-3.1 3L.07 43.02Z"
    />
    <path
      fill="#fff"
      fill-rule="evenodd"
      d="M49.5 19a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0Z"
      clip-rule="evenodd"
    />
    <path fill="#fff" d="M45 .28v59.66l-2 .1V.19c.7.02 1.37.05 2 .1Z" />
    <path fill="#2BEE6C" d="M47.5 19a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" />
    <path
      stroke="#fff"
      stroke-opacity=".1"
      d="M.5 25.01c0-4.63 0-8.08.24-10.8.25-2.7.73-4.64 1.66-6.28a14.5 14.5 0 0 1 5.42-5.41C9.46 1.58 11.39 1.1 14.1.85A133 133 0 0 1 24.9.61h10.2c4.63 0 8.08 0 10.8.24 2.7.25 4.65.73 6.28 1.67a14.5 14.5 0 0 1 5.42 5.4c.93 1.65 1.41 3.58 1.66 6.3.24 2.71.24 6.16.24 10.79v10.2c0 4.64 0 8.08-.24 10.8-.25 2.7-.73 4.65-1.66 6.28a14.5 14.5 0 0 1-5.42 5.42c-1.63.93-3.57 1.41-6.28 1.66-2.72.24-6.17.24-10.8.24H24.9c-4.63 0-8.08 0-10.8-.24-2.7-.25-4.64-.73-6.28-1.66a14.5 14.5 0 0 1-5.42-5.42C1.47 50.66 1 48.72.74 46.01A133 133 0 0 1 .5 35.2v-10.2Z"
    />
  </g>
  <defs>
    <clipPath id="a"><path fill="#fff" d="M0 0h60v60H0z" /></clipPath>
  </defs>
</svg>`,Xe=K`<svg fill="none" viewBox="0 0 60 60">
  <g clip-path="url(#a)">
    <rect width="60" height="60" fill="#C653C6" rx="30" />
    <path
      fill="#E87DE8"
      d="M57.98.01v19.5a4.09 4.09 0 0 0-2.63 2.29L50.7 34.2a2 2 0 0 1-2.5 1.2l-2.53-.84a2 2 0 0 0-2.42 1l-2.97 5.94a2 2 0 0 1-3.7-.32L32.8 28.6a2 2 0 0 0-3.02-1.09l-3.35 2.23a2 2 0 0 0-.64.7l-5.3 9.72a2 2 0 0 1-1.43 1.01l-4.13.69a2 2 0 0 1-1.61-.44l-3.9-3.24a2 2 0 0 0-2.69.12L2.1 42.93.02 43V.01h57.96Z"
    />
    <path
      fill="#fff"
      d="m61.95 16.94.05 2.1-3.85 1.28a3 3 0 0 0-1.86 1.79l-4.65 12.4a3 3 0 0 1-3.76 1.8l-2.53-.84a1 1 0 0 0-1.2.5l-2.98 5.94a3 3 0 0 1-5.55-.48l-3.78-12.58a1 1 0 0 0-1.5-.55l-3.35 2.23a1 1 0 0 0-.32.35l-5.3 9.72a3 3 0 0 1-2.14 1.52l-4.14.69a3 3 0 0 1-2.41-.66l-3.9-3.24a1 1 0 0 0-1.34.06l-5.28 5.28c-.05-.84-.08-1.75-.1-2.73l3.97-3.96a3 3 0 0 1 4.04-.19l3.89 3.25a1 1 0 0 0 .8.21l4.14-.68a1 1 0 0 0 .71-.51l5.3-9.71a3 3 0 0 1 .97-1.06l3.34-2.23a3 3 0 0 1 4.54 1.63l3.77 12.58a1 1 0 0 0 1.86.16l2.96-5.93a3 3 0 0 1 3.64-1.5l2.52.83a1 1 0 0 0 1.25-.6l4.66-12.4a5 5 0 0 1 3.1-2.99l4.43-1.48Z"
    />
    <path
      fill="#fff"
      fill-rule="evenodd"
      d="M35.5 27a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0Z"
      clip-rule="evenodd"
    />
    <path fill="#fff" d="M31 0v60h-2V0h2Z" />
    <path fill="#E87DE8" d="M33.5 27a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" />
  </g>
  <rect width="59" height="59" x=".5" y=".5" stroke="#fff" stroke-opacity=".1" rx="29.5" />
  <defs>
    <clipPath id="a"><rect width="60" height="60" fill="#fff" rx="30" /></clipPath>
  </defs>
</svg> `,tr=K`<svg fill="none" viewBox="0 0 60 60">
  <g clip-path="url(#a)">
    <rect width="60" height="60" fill="#987DE8" rx="30" />
    <path
      fill="#fff"
      fill-rule="evenodd"
      d="m15.48 28.37 11.97-19.3a3 3 0 0 1 5.1 0l11.97 19.3a6 6 0 0 1 .9 3.14v.03a6 6 0 0 1-1.16 3.56L33.23 50.2a4 4 0 0 1-6.46 0L15.73 35.1a6 6 0 0 1-1.15-3.54v-.03a6 6 0 0 1 .9-3.16Z"
      clip-rule="evenodd"
    />
    <path
      fill="#643CDD"
      d="M30.84 10.11a1 1 0 0 0-.84-.46V24.5l12.6 5.53a2 2 0 0 0-.28-1.4L30.84 10.11Z"
    />
    <path
      fill="#BDADEB"
      d="M30 9.65a1 1 0 0 0-.85.46L17.66 28.64a2 2 0 0 0-.26 1.39L30 24.5V9.65Z"
    />
    <path
      fill="#643CDD"
      d="M30 50.54a1 1 0 0 0 .8-.4l11.24-15.38c.3-.44-.2-1-.66-.73l-9.89 5.68a3 3 0 0 1-1.5.4v10.43Z"
    />
    <path
      fill="#BDADEB"
      d="m17.97 34.76 11.22 15.37c.2.28.5.41.8.41V40.11a3 3 0 0 1-1.49-.4l-9.88-5.68c-.47-.27-.97.3-.65.73Z"
    />
    <path
      fill="#401AB3"
      d="M42.6 30.03 30 24.5v13.14a3 3 0 0 0 1.5-.4l10.14-5.83a2 2 0 0 0 .95-1.38Z"
    />
    <path
      fill="#7C5AE2"
      d="M30 37.64V24.46l-12.6 5.57a2 2 0 0 0 .97 1.39l10.13 5.82a3 3 0 0 0 1.5.4Z"
    />
  </g>
  <rect width="59" height="59" x=".5" y=".5" stroke="#fff" stroke-opacity=".1" rx="29.5" />
  <defs>
    <clipPath id="a"><rect width="60" height="60" fill="#fff" rx="30" /></clipPath>
  </defs>
</svg> `,er=K`<svg fill="none" viewBox="0 0 60 60">
  <rect width="60" height="60" fill="#1DC956" rx="3" />
  <path
    fill="#1FAD7E"
    stroke="#fff"
    stroke-width="2"
    d="m30.49 29.13-.49-.27-.49.27-12.77 7.1-.05.02c-.86.48-1.58.88-2.1 1.24-.54.37-1.04.81-1.28 1.45a3 3 0 0 0 0 2.12c.24.63.74 1.08 1.27 1.45.53.36 1.25.76 2.11 1.24l.05.03 6.33 3.51.17.1c2.33 1.3 3.72 2.06 5.22 2.32a9 9 0 0 0 3.08 0c1.5-.26 2.9-1.03 5.22-2.32l.18-.1 6.32-3.51.05-.03a26.9 26.9 0 0 0 2.1-1.24 3.21 3.21 0 0 0 1.28-1.45l-.94-.35.94.35a3 3 0 0 0 0-2.12l-.94.35.94-.35a3.21 3.21 0 0 0-1.27-1.45c-.53-.36-1.25-.76-2.11-1.24l-.05-.03-12.77-7.1Z"
  />
  <path
    fill="#2BEE6C"
    stroke="#fff"
    stroke-width="2"
    d="m30.49 19.13-.49-.27-.49.27-12.77 7.1-.05.02c-.86.48-1.58.88-2.1 1.24-.54.37-1.04.81-1.28 1.45a3 3 0 0 0 0 2.12c.24.63.74 1.08 1.27 1.45.53.36 1.25.76 2.11 1.24l.05.03 6.33 3.51.17.1c2.33 1.3 3.72 2.06 5.22 2.32a9 9 0 0 0 3.08 0c1.5-.26 2.9-1.03 5.22-2.32l.18-.1 6.32-3.51.05-.03a26.9 26.9 0 0 0 2.1-1.24 3.21 3.21 0 0 0 1.28-1.45l-.94-.35.94.35a3 3 0 0 0 0-2.12l-.94.35.94-.35a3.21 3.21 0 0 0-1.27-1.45c-.53-.36-1.25-.76-2.11-1.24l-.05-.03-12.77-7.1Z"
  />
  <path
    fill="#86F999"
    stroke="#fff"
    stroke-width="2"
    d="m46.69 21.06-.94-.35.94.35a3 3 0 0 0 0-2.12l-.94.35.94-.35a3.21 3.21 0 0 0-1.27-1.45c-.53-.36-1.25-.76-2.11-1.24l-.05-.03-6.32-3.51-.18-.1c-2.33-1.3-3.72-2.06-5.22-2.33a9 9 0 0 0-3.08 0c-1.5.27-2.9 1.04-5.22 2.33l-.17.1-6.33 3.51-.05.03c-.86.48-1.58.88-2.1 1.24-.54.37-1.04.81-1.28 1.45a3 3 0 0 0 0 2.12c.24.63.74 1.08 1.27 1.45.53.36 1.25.76 2.11 1.24l.05.03 6.33 3.51.17.1c2.33 1.3 3.72 2.06 5.22 2.32a9 9 0 0 0 3.08 0c1.5-.26 2.9-1.03 5.22-2.32l.18-.1 6.32-3.51.05-.03a26.9 26.9 0 0 0 2.1-1.24 3.21 3.21 0 0 0 1.28-1.45Z"
  />
  <rect width="59" height="59" x=".5" y=".5" stroke="#fff" stroke-opacity=".1" rx="2.5" />
</svg>`,rr=K`<svg fill="none" viewBox="0 0 60 60">
  <rect width="60" height="60" fill="#C653C6" rx="3" />
  <path
    fill="#fff"
    d="M20.03 15.22C20 15.6 20 16.07 20 17v2.8c0 1.14 0 1.7-.2 2.12-.15.31-.3.5-.58.71-.37.28-1.06.42-2.43.7-.59.12-1.11.29-1.6.51a9 9 0 0 0-4.35 4.36C10 30 10 32.34 10 37c0 4.66 0 7 .84 8.8a9 9 0 0 0 4.36 4.36C17 51 19.34 51 24 51h12c4.66 0 7 0 8.8-.84a9 9 0 0 0 4.36-4.36C50 44 50 41.66 50 37c0-4.66 0-7-.84-8.8a9 9 0 0 0-4.36-4.36c-.48-.22-1-.39-1.6-.5-1.36-.29-2.05-.43-2.42-.7-.27-.22-.43-.4-.58-.72-.2-.42-.2-.98-.2-2.11V17c0-.93 0-1.4-.03-1.78a9 9 0 0 0-8.19-8.19C31.4 7 30.93 7 30 7s-1.4 0-1.78.03a9 9 0 0 0-8.19 8.19Z"
  />
  <path
    fill="#E87DE8"
    d="M22 17c0-.93 0-1.4.04-1.78a7 7 0 0 1 6.18-6.18C28.6 9 29.07 9 30 9s1.4 0 1.78.04a7 7 0 0 1 6.18 6.18c.04.39.04.85.04 1.78v4.5a1.5 1.5 0 0 1-3 0V17c0-.93 0-1.4-.08-1.78a4 4 0 0 0-3.14-3.14C31.39 12 30.93 12 30 12s-1.4 0-1.78.08a4 4 0 0 0-3.14 3.14c-.08.39-.08.85-.08 1.78v4.5a1.5 1.5 0 0 1-3 0V17Z"
  />
  <path
    fill="#E87DE8"
    fill-rule="evenodd"
    d="M12 36.62c0-4.32 0-6.48.92-8.09a7 7 0 0 1 2.61-2.61C17.14 25 19.3 25 23.62 25h6.86c.46 0 .7 0 .9.02 2.73.22 4.37 2.43 4.62 4.98.27-2.7 2.11-5 5.02-5A6.98 6.98 0 0 1 48 31.98v5.4c0 4.32 0 6.48-.92 8.09a7 7 0 0 1-2.61 2.61c-1.61.92-3.77.92-8.09.92h-5.86c-.46 0-.7 0-.9-.02-2.73-.22-4.37-2.43-4.62-4.98-.26 2.58-1.94 4.82-4.71 4.99l-.7.01c-.55 0-.82 0-1.05-.02a7 7 0 0 1-6.52-6.52c-.02-.23-.02-.5-.02-1.05v-4.79Zm21.24-.27a4 4 0 1 0-6.48 0 31.28 31.28 0 0 1 1.57 2.23c.17.4.17.81.17 1.24V42.5a1.5 1.5 0 0 0 3 0V39.82c0-.43 0-.85.17-1.24.09-.2.58-.87 1.57-2.23Z"
    clip-rule="evenodd"
  />
  <rect width="59" height="59" x=".5" y=".5" stroke="#fff" stroke-opacity=".1" rx="2.5" />
</svg>`,nr=K`<svg fill="none" viewBox="0 0 60 60">
  <g clip-path="url(#a)">
    <path
      fill="#EB8B47"
      d="M0 24.9c0-9.25 0-13.88 1.97-17.33a15 15 0 0 1 5.6-5.6C11.02 0 15.65 0 24.9 0h10.2c9.25 0 13.88 0 17.33 1.97a15 15 0 0 1 5.6 5.6C60 11.02 60 15.65 60 24.9v10.2c0 9.25 0 13.88-1.97 17.33a15 15 0 0 1-5.6 5.6C48.98 60 44.35 60 35.1 60H24.9c-9.25 0-13.88 0-17.33-1.97a15 15 0 0 1-5.6-5.6C0 48.98 0 44.35 0 35.1V24.9Z"
    />
    <path
      stroke="#062B2B"
      stroke-opacity=".1"
      d="M.5 24.9c0-4.64 0-8.08.24-10.8.25-2.7.73-4.65 1.66-6.28A14.5 14.5 0 0 1 7.82 2.4C9.46 1.47 11.39 1 14.1.74A133 133 0 0 1 24.9.5h10.2c4.63 0 8.08 0 10.8.24 2.7.25 4.65.73 6.28 1.66a14.5 14.5 0 0 1 5.42 5.42c.93 1.63 1.41 3.57 1.66 6.28.24 2.72.24 6.16.24 10.8v10.2c0 4.63 0 8.08-.24 10.8-.25 2.7-.73 4.64-1.66 6.28a14.5 14.5 0 0 1-5.42 5.41c-1.63.94-3.57 1.42-6.28 1.67-2.72.24-6.17.24-10.8.24H24.9c-4.63 0-8.08 0-10.8-.24-2.7-.25-4.64-.73-6.28-1.67a14.5 14.5 0 0 1-5.42-5.4C1.47 50.53 1 48.6.74 45.88A133 133 0 0 1 .5 35.1V24.9Z"
    />
    <path
      fill="#FF974C"
      stroke="#fff"
      stroke-width="2"
      d="M39.2 29.2a13 13 0 1 0-18.4 0l1.3 1.28a12.82 12.82 0 0 1 2.1 2.39 6 6 0 0 1 .6 1.47c.2.76.2 1.56.2 3.17v11.24c0 1.08 0 1.61.13 2.12a4 4 0 0 0 .41.98c.26.45.64.83 1.4 1.6l.3.29c.65.65.98.98 1.36 1.09.26.07.54.07.8 0 .38-.11.7-.44 1.36-1.1l3.48-3.47c.65-.65.98-.98 1.09-1.36a1.5 1.5 0 0 0 0-.8c-.1-.38-.44-.7-1.1-1.36l-.47-.48c-.65-.65-.98-.98-1.09-1.36a1.5 1.5 0 0 1 0-.8c.1-.38.44-.7 1.1-1.36l.47-.48c.65-.65.98-.98 1.09-1.36a1.5 1.5 0 0 0 0-.8c-.1-.38-.44-.7-1.1-1.36l-.48-.5c-.65-.64-.98-.97-1.08-1.35a1.5 1.5 0 0 1 0-.79c.1-.38.42-.7 1.06-1.36l5.46-5.55Z"
    />
    <circle cx="30" cy="17" r="4" fill="#EB8B47" stroke="#fff" stroke-width="2" />
  </g>
  <defs>
    <clipPath id="a"><path fill="#fff" d="M0 0h60v60H0z" /></clipPath>
  </defs>
</svg> `,ir=K`<svg fill="none" viewBox="0 0 60 60">
  <g clip-path="url(#a)">
    <rect width="60" height="60" fill="#00ACE6" rx="30" />
    <circle cx="64" cy="39" r="50" fill="#1AC6FF" stroke="#fff" stroke-width="2" />
    <circle cx="78" cy="30" r="50" fill="#4DD2FF" stroke="#fff" stroke-width="2" />
    <circle cx="72" cy="15" r="35" fill="#80DFFF" stroke="#fff" stroke-width="2" />
    <circle cx="34" cy="-17" r="45" stroke="#fff" stroke-width="2" />
    <circle cx="34" cy="-5" r="50" stroke="#fff" stroke-width="2" />
    <circle cx="30" cy="45" r="4" fill="#4DD2FF" stroke="#fff" stroke-width="2" />
    <circle cx="39.5" cy="27.5" r="4" fill="#80DFFF" stroke="#fff" stroke-width="2" />
    <circle cx="16" cy="24" r="4" fill="#19C6FF" stroke="#fff" stroke-width="2" />
  </g>
  <rect width="59" height="59" x=".5" y=".5" stroke="#062B2B" stroke-opacity=".1" rx="29.5" />
  <defs>
    <clipPath id="a"><rect width="60" height="60" fill="#fff" rx="30" /></clipPath>
  </defs>
</svg>`,sr=K`<svg fill="none" viewBox="0 0 60 60">
  <g clip-path="url(#a)">
    <rect width="60" height="60" fill="#C653C6" rx="3" />
    <path
      fill="#E87DE8"
      stroke="#fff"
      stroke-width="2"
      d="M52.1 47.34c0-4.24-1.44-9.55-5.9-12.4a2.86 2.86 0 0 0-1.6-3.89v-.82c0-1.19-.52-2.26-1.35-3a4.74 4.74 0 0 0-2.4-6.26v-5.5a11.31 11.31 0 1 0-22.63 0v2.15a3.34 3.34 0 0 0-1.18 5.05 4.74 4.74 0 0 0-.68 6.44A5.22 5.22 0 0 0 14 35.92c-3.06 4.13-6.1 8.3-6.1 15.64 0 2.67.37 4.86.74 6.39a20.3 20.3 0 0 0 .73 2.39l.02.04v.01l.92-.39-.92.4.26.6h38.26l.3-.49-.87-.51.86.5.02-.01.03-.07a16.32 16.32 0 0 0 .57-1.05c.36-.72.85-1.74 1.33-2.96a25.51 25.51 0 0 0 1.94-9.07Z"
    />
    <path
      fill="#fff"
      fill-rule="evenodd"
      d="M26.5 29.5c-3-.5-5.5-3-5.5-7v-7c0-.47 0-.7.03-.9a3 3 0 0 1 2.58-2.57c.2-.03.42-.03.89-.03 2 0 2.5-2.5 2.5-2.5s0 2.5 2.5 2.5c1.4 0 2.1 0 2.65.23a3 3 0 0 1 1.62 1.62c.23.55.23 1.25.23 2.65v6c0 4-3 7-6.5 7 1.35.23 4 0 6.5-2v9.53C34 38.5 31.5 40 28 40s-6-1.5-6-2.97L24 34l2.5 1.5v-6ZM26 47h4.5c2.5 0 3 4 3 5.5h-3l-1-1.5H26v-4Zm-6.25 5.5H24V57h-8c0-1 1-4.5 3.75-4.5Z"
      clip-rule="evenodd"
    />
  </g>
  <rect width="59" height="59" x=".5" y=".5" stroke="#fff" stroke-opacity=".1" rx="2.5" />
  <defs>
    <clipPath id="a"><rect width="60" height="60" fill="#fff" rx="3" /></clipPath>
  </defs>
</svg> `,or=K`<svg fill="none" viewBox="0 0 60 60">
  <rect width="60" height="60" fill="#794CFF" rx="3" />
  <path
    fill="#987DE8"
    stroke="#fff"
    stroke-width="2"
    d="M33 22.5v-1H16v5H8.5V36H13v-5h3v7.5h17V31h1v7.5h17v-17H34v5h-1v-4Z"
  />
  <path fill="#fff" d="M37.5 25h10v10h-10z" />
  <path fill="#4019B2" d="M42.5 25h5v10h-5z" />
  <path fill="#fff" d="M19.5 25h10v10h-10z" />
  <path fill="#4019B2" d="M24.5 25h5v10h-5z" />
  <path fill="#fff" d="M12 30.5h4V37h-4v-6.5Z" />
  <rect width="59" height="59" x=".5" y=".5" stroke="#fff" stroke-opacity=".1" rx="2.5" />
</svg>`,ar=K`<svg
  viewBox="0 0 60 60"
  fill="none"
>
  <g clip-path="url(#1)">
    <rect width="60" height="60" rx="30" fill="#00ACE6" />
    <path
      d="M59 73C59 89.0163 46.0163 102 30 102C13.9837 102 1 89.0163 1 73C1 56.9837 12 44 30 44C48 44 59 56.9837 59 73Z"
      fill="#1AC6FF"
      stroke="white"
      stroke-width="2"
    />
    <path
      d="M18.6904 19.9015C19.6264 15.3286 23.3466 11.8445 27.9708 11.2096C29.3231 11.024 30.6751 11.0238 32.0289 11.2096C36.6532 11.8445 40.3733 15.3286 41.3094 19.9015C41.4868 20.7681 41.6309 21.6509 41.7492 22.5271C41.8811 23.5041 41.8811 24.4944 41.7492 25.4715C41.6309 26.3476 41.4868 27.2304 41.3094 28.097C40.3733 32.6699 36.6532 36.154 32.0289 36.7889C30.6772 36.9744 29.3216 36.9743 27.9708 36.7889C23.3466 36.154 19.6264 32.6699 18.6904 28.097C18.513 27.2304 18.3689 26.3476 18.2506 25.4715C18.1186 24.4944 18.1186 23.5041 18.2506 22.5271C18.3689 21.6509 18.513 20.7681 18.6904 19.9015Z"
      fill="#1AC6FF"
      stroke="white"
      stroke-width="2"
    />
    <circle cx="24.5" cy="23.5" r="1.5" fill="white" />
    <circle cx="35.5" cy="23.5" r="1.5" fill="white" />
    <path
      d="M31 20L28 28H32"
      stroke="white"
      stroke-width="2"
      stroke-linecap="round"
      stroke-linejoin="round"
    />
  </g>
  <rect x="0.5" y="0.5" width="59" height="59" rx="29.5" stroke="white" stroke-opacity="0.1" />
  <defs>
    <clipPath id="1">
      <rect width="60" height="60" rx="30" fill="white" />
    </clipPath>
  </defs>
</svg> `,cr=K`<svg viewBox="0 0 60 60" fill="none">
  <g clip-path="url(#1)">
    <path
      d="M0 24.9C0 15.6485 0 11.0228 1.97053 7.56812C3.3015 5.23468 5.23468 3.3015 7.56812 1.97053C11.0228 0 15.6485 0 24.9 0H35.1C44.3514 0 48.9772 0 52.4319 1.97053C54.7653 3.3015 56.6985 5.23468 58.0295 7.56812C60 11.0228 60 15.6485 60 24.9V35.1C60 44.3514 60 48.9772 58.0295 52.4319C56.6985 54.7653 54.7653 56.6985 52.4319 58.0295C48.9772 60 44.3514 60 35.1 60H24.9C15.6485 60 11.0228 60 7.56812 58.0295C5.23468 56.6985 3.3015 54.7653 1.97053 52.4319C0 48.9772 0 44.3514 0 35.1V24.9Z"
      fill="#794CFF"
    />
    <path
      d="M0.5 24.9C0.5 20.2652 0.50047 16.8221 0.744315 14.105C0.987552 11.3946 1.46987 9.45504 2.40484 7.81585C3.69145 5.56019 5.56019 3.69145 7.81585 2.40484C9.45504 1.46987 11.3946 0.987552 14.105 0.744315C16.8221 0.50047 20.2652 0.5 24.9 0.5H35.1C39.7348 0.5 43.1779 0.50047 45.895 0.744315C48.6054 0.987552 50.545 1.46987 52.1841 2.40484C54.4398 3.69145 56.3086 5.56019 57.5952 7.81585C58.5301 9.45504 59.0124 11.3946 59.2557 14.105C59.4995 16.8221 59.5 20.2652 59.5 24.9V35.1C59.5 39.7348 59.4995 43.1779 59.2557 45.895C59.0124 48.6054 58.5301 50.545 57.5952 52.1841C56.3086 54.4398 54.4398 56.3086 52.1841 57.5952C50.545 58.5301 48.6054 59.0124 45.895 59.2557C43.1779 59.4995 39.7348 59.5 35.1 59.5H24.9C20.2652 59.5 16.8221 59.4995 14.105 59.2557C11.3946 59.0124 9.45504 58.5301 7.81585 57.5952C5.56019 56.3086 3.69145 54.4398 2.40484 52.1841C1.46987 50.545 0.987552 48.6054 0.744315 45.895C0.50047 43.1779 0.5 39.7348 0.5 35.1V24.9Z"
      stroke="#062B2B"
      stroke-opacity="0.1"
    />
    <path
      d="M35.1403 31.5016C35.1193 30.9637 35.388 30.4558 35.8446 30.1707C36.1207 29.9982 36.4761 29.8473 36.7921 29.7685C37.3143 29.6382 37.8664 29.7977 38.2386 30.1864C38.8507 30.8257 39.3004 31.6836 39.8033 32.408C40.2796 33.0942 41.4695 33.2512 41.9687 32.5047C42.4839 31.7341 42.9405 30.8229 43.572 30.1399C43.9375 29.7447 44.4866 29.5756 45.0111 29.6967C45.3283 29.7701 45.6863 29.9147 45.9655 30.0823C46.4269 30.3595 46.7045 30.8626 46.6928 31.4008C46.6731 32.3083 46.3764 33.2571 46.2158 34.1473C46.061 35.0048 46.9045 35.8337 47.7592 35.664C48.6464 35.4878 49.5899 35.1747 50.497 35.1391C51.0348 35.1181 51.5427 35.3868 51.8279 35.8433C52.0004 36.1195 52.1513 36.4749 52.2301 36.7908C52.3604 37.3131 52.2009 37.8651 51.8121 38.2374C51.1729 38.8495 50.3151 39.2991 49.5908 39.8019C48.9046 40.2782 48.7473 41.4683 49.4939 41.9675C50.2644 42.4827 51.1757 42.9393 51.8587 43.5708C52.2539 43.9362 52.423 44.4854 52.3018 45.0099C52.2285 45.3271 52.0839 45.6851 51.9162 45.9642C51.6391 46.4257 51.1359 46.7032 50.5978 46.6916C49.6903 46.6719 48.7417 46.3753 47.8516 46.2146C46.9939 46.0598 46.1648 46.9035 46.3346 47.7583C46.5108 48.6454 46.8239 49.5888 46.8594 50.4958C46.8805 51.0336 46.6117 51.5415 46.1552 51.8267C45.879 51.9992 45.5236 52.15 45.2077 52.2289C44.6854 52.3592 44.1334 52.1997 43.7611 51.8109C43.1491 51.1718 42.6996 50.314 42.1968 49.5897C41.7203 48.9034 40.5301 48.7463 40.0309 49.493C39.5157 50.2634 39.0592 51.1746 38.4278 51.8574C38.0623 52.2527 37.5132 52.4218 36.9887 52.3006C36.6715 52.2273 36.3135 52.0826 36.0343 51.915C35.5729 51.6379 35.2953 51.1347 35.307 50.5966C35.3267 49.6891 35.6233 48.7405 35.7839 47.8505C35.9388 46.9928 35.0951 46.1636 34.2402 46.3334C33.3531 46.5096 32.4098 46.8227 31.5028 46.8582C30.9649 46.8793 30.457 46.6105 30.1719 46.154C29.9994 45.8778 29.8485 45.5224 29.7697 45.2065C29.6394 44.6842 29.7989 44.1322 30.1877 43.7599C30.8269 43.1479 31.6847 42.6982 32.4091 42.1954C33.0954 41.7189 33.2522 40.5289 32.5056 40.0297C31.7351 39.5145 30.824 39.058 30.1411 38.4265C29.7459 38.0611 29.5768 37.5119 29.698 36.9875C29.7713 36.6702 29.9159 36.3122 30.0836 36.0331C30.3607 35.5717 30.8638 35.2941 31.402 35.3058C32.3095 35.3255 33.2583 35.6221 34.1485 35.7828C35.006 35.9376 35.8349 35.094 35.6652 34.2393C35.489 33.3521 35.1759 32.4087 35.1403 31.5016Z"
      fill="#906EF7"
      stroke="white"
      stroke-width="2"
    />
    <path
      d="M20.7706 8.22357C20.9036 7.51411 21.5231 7 22.2449 7H23.7551C24.4769 7 25.0964 7.51411 25.2294 8.22357C25.5051 9.69403 25.4829 11.6321 27.1202 12.2606C27.3092 12.3331 27.4958 12.4105 27.6798 12.4926C29.2818 13.2072 30.6374 11.8199 31.8721 10.9752C32.4678 10.5676 33.2694 10.6421 33.7798 11.1525L34.8477 12.2204C35.3581 12.7308 35.4326 13.5323 35.025 14.128C34.1802 15.3627 32.7931 16.7183 33.5077 18.3202C33.5898 18.5043 33.6672 18.6909 33.7398 18.88C34.3683 20.5171 36.3061 20.4949 37.7764 20.7706C38.4859 20.9036 39 21.5231 39 22.2449V23.7551C39 24.4769 38.4859 25.0964 37.7764 25.2294C36.3061 25.5051 34.3685 25.483 33.7401 27.1201C33.6675 27.3093 33.59 27.4961 33.5079 27.6803C32.7934 29.282 34.1803 30.6374 35.025 31.8719C35.4326 32.4677 35.3581 33.2692 34.8477 33.7796L33.7798 34.8475C33.2694 35.3579 32.4678 35.4324 31.8721 35.0248C30.6376 34.1801 29.2823 32.7934 27.6806 33.508C27.4962 33.5903 27.3093 33.6678 27.12 33.7405C25.483 34.3688 25.5051 36.3062 25.2294 37.7764C25.0964 38.4859 24.4769 39 23.7551 39H22.2449C21.5231 39 20.9036 38.4859 20.7706 37.7764C20.4949 36.3062 20.517 34.3688 18.88 33.7405C18.6908 33.6678 18.5039 33.5903 18.3196 33.5081C16.7179 32.7936 15.3625 34.1804 14.1279 35.0251C13.5322 35.4327 12.7307 35.3582 12.2203 34.8478L11.1524 33.7799C10.642 33.2695 10.5675 32.4679 10.9751 31.8722C11.8198 30.6376 13.2067 29.2822 12.4922 27.6804C12.41 27.4962 12.3325 27.3093 12.2599 27.1201C11.6315 25.483 9.69392 25.5051 8.22357 25.2294C7.51411 25.0964 7 24.4769 7 23.7551V22.2449C7 21.5231 7.51411 20.9036 8.22357 20.7706C9.69394 20.4949 11.6317 20.5171 12.2602 18.88C12.3328 18.6909 12.4103 18.5042 12.4924 18.3201C13.207 16.7181 11.8198 15.3625 10.975 14.1278C10.5674 13.5321 10.6419 12.7305 11.1523 12.2201L12.2202 11.1522C12.7306 10.6418 13.5322 10.5673 14.1279 10.9749C15.3626 11.8197 16.7184 13.2071 18.3204 12.4925C18.5044 12.4105 18.6909 12.3331 18.8799 12.2606C20.5171 11.6321 20.4949 9.69403 20.7706 8.22357Z"
      fill="#906EF7"
      stroke="white"
      stroke-width="2"
    />
    <circle cx="23" cy="23" r="6" fill="#794CFF" stroke="white" stroke-width="2" />
    <circle cx="41" cy="41" r="4" fill="#794CFF" stroke="white" stroke-width="2" />
  </g>
  <defs>
    <clipPath id="1">
      <rect width="60" height="60" fill="white" />
    </clipPath>
  </defs>
</svg> `,lr=l`
  :host {
    display: block;
    width: 55px;
    height: 55px;
  }
`;var ur=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};const hr={browser:Je,dao:Qe,defi:Ye,defiAlt:Xe,eth:tr,layers:er,lock:rr,login:nr,network:ir,nft:sr,noun:or,profile:ar,system:cr};let dr=class extends ht{constructor(){super(...arguments),this.name="browser"}render(){return G`${hr[this.name]}`}};dr.styles=[bt,lr],ur([Ct()],dr.prototype,"name",void 0),dr=ur([xt("wui-visual")],dr);const pr=t=>t??J,fr={getSpacingStyles:(t,e)=>Array.isArray(t)?t[e]?`var(--wui-spacing-${t[e]})`:void 0:"string"==typeof t?`var(--wui-spacing-${t})`:void 0,getFormattedDate:t=>new Intl.DateTimeFormat("en-US",{month:"short",day:"numeric"}).format(t),getHostName:t=>new URL(t).hostname,getTruncateString:({string:t,charsStart:e,charsEnd:r,truncate:n})=>t.length<=e+r?t:"end"===n?`${t.substring(0,e)}...`:"start"===n?`...${t.substring(t.length-r)}`:`${t.substring(0,Math.floor(e))}...${t.substring(t.length-Math.floor(r))}`,generateAvatarColors(t){const e=t.toLowerCase().replace(/^0x/iu,"").substring(0,6),r=this.hexToRgb(e),n=[];for(let t=0;t<5;t+=1){const e=this.tintColor(r,.15*t);n.push(`rgb(${e[0]}, ${e[1]}, ${e[2]})`)}return`\n    --local-color-1: ${n[0]};\n    --local-color-2: ${n[1]};\n    --local-color-3: ${n[2]};\n    --local-color-4: ${n[3]};\n    --local-color-5: ${n[4]};\n   `},hexToRgb(t){const e=parseInt(t,16);return[e>>16&255,e>>8&255,255&e]},tintColor(t,e){const[r,n,i]=t;return[Math.round(r+(255-r)*e),Math.round(n+(255-n)*e),Math.round(i+(255-i)*e)]},isNumber:t=>/^[0-9]+$/u.test(t),getColorTheme:t=>t||("undefined"!=typeof window&&window.matchMedia?window.matchMedia("(prefers-color-scheme: dark)").matches?"dark":"light":"dark")},mr=l`
  :host {
    display: flex;
    width: inherit;
    height: inherit;
  }
`;var gr=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let yr=class extends ht{render(){return this.style.cssText=`\n      flex-direction: ${this.flexDirection};\n      flex-wrap: ${this.flexWrap};\n      flex-basis: ${this.flexBasis};\n      flex-grow: ${this.flexGrow};\n      flex-shrink: ${this.flexShrink};\n      align-items: ${this.alignItems};\n      justify-content: ${this.justifyContent};\n      column-gap: ${this.columnGap&&`var(--wui-spacing-${this.columnGap})`};\n      row-gap: ${this.rowGap&&`var(--wui-spacing-${this.rowGap})`};\n      gap: ${this.gap&&`var(--wui-spacing-${this.gap})`};\n      padding-top: ${this.padding&&fr.getSpacingStyles(this.padding,0)};\n      padding-right: ${this.padding&&fr.getSpacingStyles(this.padding,1)};\n      padding-bottom: ${this.padding&&fr.getSpacingStyles(this.padding,2)};\n      padding-left: ${this.padding&&fr.getSpacingStyles(this.padding,3)};\n      margin-top: ${this.margin&&fr.getSpacingStyles(this.margin,0)};\n      margin-right: ${this.margin&&fr.getSpacingStyles(this.margin,1)};\n      margin-bottom: ${this.margin&&fr.getSpacingStyles(this.margin,2)};\n      margin-left: ${this.margin&&fr.getSpacingStyles(this.margin,3)};\n    `,G`<slot></slot>`}};yr.styles=[bt,mr],gr([Ct()],yr.prototype,"flexDirection",void 0),gr([Ct()],yr.prototype,"flexWrap",void 0),gr([Ct()],yr.prototype,"flexBasis",void 0),gr([Ct()],yr.prototype,"flexGrow",void 0),gr([Ct()],yr.prototype,"flexShrink",void 0),gr([Ct()],yr.prototype,"alignItems",void 0),gr([Ct()],yr.prototype,"justifyContent",void 0),gr([Ct()],yr.prototype,"columnGap",void 0),gr([Ct()],yr.prototype,"rowGap",void 0),gr([Ct()],yr.prototype,"gap",void 0),gr([Ct()],yr.prototype,"padding",void 0),gr([Ct()],yr.prototype,"margin",void 0),yr=gr([xt("wui-flex")],yr);const wr=l`
  :host {
    display: block;
    width: var(--wui-icon-box-size-xl);
    height: var(--wui-icon-box-size-xl);
    border-radius: var(--wui-border-radius-3xl);
    box-shadow: 0 0 0 8px var(--wui-gray-glass-005);
    overflow: hidden;
    position: relative;
  }

  :host([data-variant='generated']) {
    --mixed-local-color-1: var(--local-color-1);
    --mixed-local-color-2: var(--local-color-2);
    --mixed-local-color-3: var(--local-color-3);
    --mixed-local-color-4: var(--local-color-4);
    --mixed-local-color-5: var(--local-color-5);
  }

  @supports (background: color-mix(in srgb, white 50%, black)) {
    :host([data-variant='generated']) {
      --mixed-local-color-1: color-mix(
        in srgb,
        var(--w3m-color-mix) var(--w3m-color-mix-strength),
        var(--local-color-1)
      );
      --mixed-local-color-2: color-mix(
        in srgb,
        var(--w3m-color-mix) var(--w3m-color-mix-strength),
        var(--local-color-2)
      );
      --mixed-local-color-3: color-mix(
        in srgb,
        var(--w3m-color-mix) var(--w3m-color-mix-strength),
        var(--local-color-3)
      );
      --mixed-local-color-4: color-mix(
        in srgb,
        var(--w3m-color-mix) var(--w3m-color-mix-strength),
        var(--local-color-4)
      );
      --mixed-local-color-5: color-mix(
        in srgb,
        var(--w3m-color-mix) var(--w3m-color-mix-strength),
        var(--local-color-5)
      );
    }
  }

  :host([data-variant='generated']) {
    box-shadow: 0 0 0 8px var(--wui-gray-glass-005);
    background: radial-gradient(
      75.29% 75.29% at 64.96% 24.36%,
      #fff 0.52%,
      var(--mixed-local-color-5) 31.25%,
      var(--mixed-local-color-3) 51.56%,
      var(--mixed-local-color-2) 65.63%,
      var(--mixed-local-color-1) 82.29%,
      var(--mixed-local-color-4) 100%
    );
  }

  :host([data-variant='default']) {
    box-shadow: 0 0 0 8px var(--wui-gray-glass-005);
    background: radial-gradient(
      75.29% 75.29% at 64.96% 24.36%,
      #fff 0.52%,
      #f5ccfc 31.25%,
      #dba4f5 51.56%,
      #9a8ee8 65.63%,
      #6493da 82.29%,
      #6ebdea 100%
    );
  }
`;var br=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let vr=class extends ht{constructor(){super(...arguments),this.imageSrc=void 0,this.alt=void 0,this.address=void 0}render(){return G`${this.visualTemplate()}`}visualTemplate(){if(this.imageSrc)return this.dataset.variant="image",G`<wui-image src=${this.imageSrc} alt=${this.alt??"avatar"}></wui-image>`;if(this.address){this.dataset.variant="generated";const t=fr.generateAvatarColors(this.address);return this.style.cssText=t,null}return this.dataset.variant="default",null}};vr.styles=[bt,wr],br([Ct()],vr.prototype,"imageSrc",void 0),br([Ct()],vr.prototype,"alt",void 0),br([Ct()],vr.prototype,"address",void 0),vr=br([xt("wui-avatar")],vr);const Er=l`
  :host {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    position: relative;
    overflow: hidden;
    background-color: var(--wui-gray-glass-020);
    border-radius: var(--local-border-radius);
    box-shadow: 0 0 0 1px var(--local-border);
    width: var(--local-size);
    height: var(--local-size);
    min-height: var(--local-size);
    min-width: var(--local-size);
  }

  @supports (background: color-mix(in srgb, white 50%, black)) {
    :host {
      background-color: color-mix(in srgb, var(--local-bg-value) var(--local-bg-mix), transparent);
    }
  }
`;var xr=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let _r=class extends ht{constructor(){super(...arguments),this.size="md",this.backgroundColor="accent-100",this.iconColor="accent-100",this.background="transparent",this.border=!1,this.borderColor="wui-color-bg-125",this.icon="copy"}render(){const t=this.iconSize||this.size,e="lg"===this.size,r="xl"===this.size,n=e?"12%":"16%",i=e?"xxs":r?"s":"3xl",s="gray"===this.background,o="opaque"===this.background,a="accent-100"===this.backgroundColor&&o||"success-100"===this.backgroundColor&&o||"error-100"===this.backgroundColor&&o||"inverse-100"===this.backgroundColor&&o;let c=`var(--wui-color-${this.backgroundColor})`;return a?c=`var(--wui-icon-box-bg-${this.backgroundColor})`:s&&(c=`var(--wui-gray-${this.backgroundColor})`),this.style.cssText=`\n       --local-bg-value: ${c};\n       --local-bg-mix: ${a||s?"100%":n};\n       --local-border-radius: var(--wui-border-radius-${i});\n       --local-size: var(--wui-icon-box-size-${this.size});\n       --local-border: ${"wui-color-bg-125"===this.borderColor?"2px":"1px"} solid ${this.border?`var(--${this.borderColor})`:"transparent"}\n   `,G` <wui-icon color=${this.iconColor} size=${t} name=${this.icon}></wui-icon> `}};_r.styles=[bt,vt,Er],xr([Ct()],_r.prototype,"size",void 0),xr([Ct()],_r.prototype,"backgroundColor",void 0),xr([Ct()],_r.prototype,"iconColor",void 0),xr([Ct()],_r.prototype,"iconSize",void 0),xr([Ct()],_r.prototype,"background",void 0),xr([Ct({type:Boolean})],_r.prototype,"border",void 0),xr([Ct()],_r.prototype,"borderColor",void 0),xr([Ct()],_r.prototype,"icon",void 0),_r=xr([xt("wui-icon-box")],_r);const Ar=l`
  :host {
    display: block;
  }

  button {
    border-radius: var(--wui-border-radius-3xl);
    background: var(--wui-gray-glass-002);
    display: flex;
    gap: var(--wui-spacing-xs);
    padding: var(--wui-spacing-3xs) var(--wui-spacing-xs) var(--wui-spacing-3xs)
      var(--wui-spacing-xs);
    border: 1px solid var(--wui-gray-glass-005);
  }

  button:disabled {
    background: var(--wui-gray-glass-015);
  }

  button:disabled > wui-text {
    color: var(--wui-gray-glass-015);
  }

  button:disabled > wui-flex > wui-text {
    color: var(--wui-gray-glass-015);
  }

  button:disabled > wui-image,
  button:disabled > wui-icon-box,
  button:disabled > wui-flex > wui-avatar {
    filter: grayscale(1);
  }

  button:has(wui-image) {
    padding: var(--wui-spacing-3xs) var(--wui-spacing-3xs) var(--wui-spacing-3xs)
      var(--wui-spacing-xs);
  }

  wui-text {
    color: var(--wui-color-fg-100);
  }

  wui-flex > wui-text {
    color: var(--wui-color-fg-200);
    transition: all var(--wui-ease-out-power-1) var(--wui-duration-lg);
  }

  wui-image,
  wui-icon-box {
    border-radius: var(--wui-border-radius-3xl);
    width: 24px;
    height: 24px;
    box-shadow: 0 0 0 2px var(--wui-gray-glass-005);
  }

  wui-flex {
    border-radius: var(--wui-border-radius-3xl);
    border: 1px solid var(--wui-gray-glass-005);
    background: var(--wui-gray-glass-005);
    padding: 4px var(--wui-spacing-m) 4px var(--wui-spacing-xxs);
  }

  button.local-no-balance {
    border-radius: 0px;
    border: none;
    background: transparent;
  }

  wui-avatar {
    width: 20px;
    height: 20px;
    box-shadow: 0 0 0 2px var(--wui-accent-glass-010);
  }

  @media (max-width: 500px) {
    button {
      gap: 0px;
      padding: var(--wui-spacing-3xs) var(--wui-spacing-xs) !important;
      height: 32px;
    }
    wui-image,
    wui-icon-box,
    button > wui-text {
      visibility: hidden;
      width: 0px;
      height: 0px;
    }
    button {
      border-radius: 0px;
      border: none;
      background: transparent;
      padding: 0px;
    }
  }

  @media (hover: hover) and (pointer: fine) {
    button:hover:enabled > wui-flex > wui-text {
      color: var(--wui-color-fg-175);
    }

    button:active:enabled > wui-flex > wui-text {
      color: var(--wui-color-fg-175);
    }
  }
`;var kr=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Sr=class extends ht{constructor(){super(...arguments),this.networkSrc=void 0,this.avatarSrc=void 0,this.balance=void 0,this.disabled=!1,this.isProfileName=!1,this.address="",this.charsStart=4,this.charsEnd=6}render(){return G`
      <button
        ?disabled=${this.disabled}
        class=${pr(this.balance?void 0:"local-no-balance")}
      >
        ${this.balanceTemplate()}
        <wui-flex gap="xxs" alignItems="center">
          <wui-avatar
            .imageSrc=${this.avatarSrc}
            alt=${this.address}
            address=${this.address}
          ></wui-avatar>
          <wui-text variant="paragraph-600" color="inherit">
            ${fr.getTruncateString({string:this.address,charsStart:this.isProfileName?18:this.charsStart,charsEnd:this.isProfileName?0:this.charsEnd,truncate:this.isProfileName?"end":"middle"})}
          </wui-text>
        </wui-flex>
      </button>
    `}balanceTemplate(){if(this.balance){const t=this.networkSrc?G`<wui-image src=${this.networkSrc}></wui-image>`:G`
            <wui-icon-box
              size="sm"
              iconColor="fg-200"
              backgroundColor="fg-300"
              icon="networkPlaceholder"
            ></wui-icon-box>
          `;return G`
        ${t}
        <wui-text variant="paragraph-600" color="inherit"> ${this.balance} </wui-text>
      `}return null}};Sr.styles=[bt,vt,Ar],kr([Ct()],Sr.prototype,"networkSrc",void 0),kr([Ct()],Sr.prototype,"avatarSrc",void 0),kr([Ct()],Sr.prototype,"balance",void 0),kr([Ct({type:Boolean})],Sr.prototype,"disabled",void 0),kr([Ct({type:Boolean})],Sr.prototype,"isProfileName",void 0),kr([Ct()],Sr.prototype,"address",void 0),kr([Ct()],Sr.prototype,"charsStart",void 0),kr([Ct()],Sr.prototype,"charsEnd",void 0),Sr=kr([xt("wui-account-button")],Sr);const Cr=l`
  :host {
    position: relative;
    background-color: var(--wui-gray-glass-002);
    display: flex;
    justify-content: center;
    align-items: center;
    width: var(--local-size);
    height: var(--local-size);
    border-radius: inherit;
    border-radius: var(--local-border-radius);
  }

  :host > wui-flex {
    overflow: hidden;
    border-radius: inherit;
    border-radius: var(--local-border-radius);
  }

  :host::after {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    border-radius: inherit;
    border: 1px solid var(--wui-gray-glass-010);
    pointer-events: none;
  }

  :host([name='Extension'])::after {
    border: 1px solid var(--wui-accent-glass-010);
  }

  :host([data-wallet-icon='allWallets']) {
    background-color: var(--wui-all-wallets-bg-100);
  }

  :host([data-wallet-icon='allWallets'])::after {
    border: 1px solid var(--wui-accent-glass-010);
  }

  wui-icon[data-parent-size='inherit'] {
    width: 75%;
    height: 75%;
    align-items: center;
  }

  wui-icon[data-parent-size='sm'] {
    width: 18px;
    height: 18px;
  }

  wui-icon[data-parent-size='md'] {
    width: 24px;
    height: 24px;
  }

  wui-icon[data-parent-size='lg'] {
    width: 42px;
    height: 42px;
  }

  wui-icon[data-parent-size='full'] {
    width: 100%;
    height: 100%;
  }

  :host > wui-icon-box {
    position: absolute;
    overflow: hidden;
    right: -1px;
    bottom: -2px;
    z-index: 1;
    border: 2px solid var(--wui-color-bg-base-150, #1e1f1f);
    padding: 1px;
  }
`;var Mr=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Ir=class extends ht{constructor(){super(...arguments),this.size="md",this.name="",this.installed=!1,this.badgeSize="xs"}render(){let t="xxs";return t="lg"===this.size?"m":"md"===this.size?"xs":"xxs",this.style.cssText=`\n       --local-border-radius: var(--wui-border-radius-${t});\n       --local-size: var(--wui-wallet-image-size-${this.size});\n   `,this.walletIcon&&(this.dataset.walletIcon=this.walletIcon),G`
      <wui-flex justifyContent="center" alignItems="center"> ${this.templateVisual()} </wui-flex>
      ${this.templateInstalledBadge()}
    `}templateVisual(){return this.imageSrc?G`<wui-image src=${this.imageSrc} alt=${this.name}></wui-image>`:this.walletIcon?G`<wui-icon
        data-parent-size="md"
        size="md"
        color="inherit"
        name=${this.walletIcon}
      ></wui-icon>`:G`<wui-icon
      data-parent-size=${this.size}
      size="inherit"
      color="inherit"
      name="walletPlaceholder"
    ></wui-icon>`}templateInstalledBadge(){return this.installed?G`
        <wui-icon-box
          size=${this.badgeSize}
          iconSize=${this.badgeSize}
          iconcolor="success-100"
          backgroundcolor="success-100"
          icon="checkmark"
          background="opaque"
        ></wui-icon-box>
      `:null}};Ir.styles=[bt,Cr],Mr([Ct()],Ir.prototype,"size",void 0),Mr([Ct()],Ir.prototype,"name",void 0),Mr([Ct()],Ir.prototype,"imageSrc",void 0),Mr([Ct()],Ir.prototype,"walletIcon",void 0),Mr([Ct({type:Boolean})],Ir.prototype,"installed",void 0),Mr([Ct()],Ir.prototype,"badgeSize",void 0),Ir=Mr([xt("wui-wallet-image")],Ir);const Pr=l`
  :host {
    position: relative;
    border-radius: var(--wui-border-radius-xxs);
    width: 40px;
    height: 40px;
    overflow: hidden;
    background: var(--wui-gray-glass-002);
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    gap: var(--wui-spacing-4xs);
    padding: 3.75px !important;
  }

  :host::after {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    border-radius: inherit;
    border: 1px solid var(--wui-gray-glass-010);
    pointer-events: none;
  }

  :host > wui-wallet-image {
    width: 14px;
    height: 14px;
    border-radius: var(--wui-border-radius-5xs);
  }

  :host > wui-flex {
    padding: 2px;
    position: fixed;
    overflow: hidden;
    left: 34px;
    bottom: 8px;
    background: var(--dark-background-150, #1e1f1f);
    border-radius: 50%;
    z-index: 2;
    display: flex;
  }
`;var Or=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Tr=class extends ht{constructor(){super(...arguments),this.walletImages=[]}render(){const t=this.walletImages.length<4;return G`${this.walletImages.slice(0,4).map((({src:t,walletName:e})=>G`
            <wui-wallet-image
              size="inherit"
              imageSrc=${t}
              name=${pr(e)}
            ></wui-wallet-image>
          `))}
      ${t?[...Array(4-this.walletImages.length)].map((()=>G` <wui-wallet-image size="inherit" name=""></wui-wallet-image>`)):null}
      <wui-flex>
        <wui-icon-box
          size="xxs"
          iconSize="xxs"
          iconcolor="success-100"
          backgroundcolor="success-100"
          icon="checkmark"
          background="opaque"
        ></wui-icon-box>
      </wui-flex>`}};Tr.styles=[bt,Pr],Or([Ct({type:Array})],Tr.prototype,"walletImages",void 0),Tr=Or([xt("wui-all-wallets-image")],Tr);const Nr=l`
  :host {
    width: var(--local-width);
    position: relative;
  }

  button {
    border: 1px solid var(--wui-gray-glass-010);
    border-radius: var(--wui-border-radius-m);
    width: var(--local-width);
  }

  button:disabled {
    border: 1px solid var(--wui-gray-glass-010);
  }

  button[data-size='sm'] {
    padding: var(--wui-spacing-xxs) var(--wui-spacing-s);
  }

  button[data-size='sm'][data-icon-left='true'] {
    padding: var(--wui-spacing-xxs) var(--wui-spacing-s) var(--wui-spacing-xxs)
      var(--wui-spacing-xs);
  }

  button[data-size='sm'][data-icon-right='true'] {
    padding: var(--wui-spacing-xxs) var(--wui-spacing-xs) var(--wui-spacing-xxs)
      var(--wui-spacing-s);
  }

  ::slotted(*) {
    transition: opacity 200ms ease-in-out;
    opacity: var(--local-opacity-100);
  }

  button > wui-text {
    transition: opacity 200ms ease-in-out;
    opacity: var(--local-opacity-100);
  }

  button[data-size='md'] {
    padding: 8.2px var(--wui-spacing-l) 9px var(--wui-spacing-l);
  }

  button[data-size='md'][data-icon-left='true'] {
    padding: 8.2px var(--wui-spacing-l) 9px var(--wui-spacing-s);
  }

  button[data-size='md'][data-icon-right='true'] {
    padding: 8.2px var(--wui-spacing-s) 9px var(--wui-spacing-l);
  }

  wui-loading-spinner {
    position: absolute;
    left: 50%;
    top: 50%;
    transition: all 200ms ease-in-out;
    transform: translate(-50%, -50%);
    opacity: var(--local-opacity-000);
  }
`;var Rr=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Br=class extends ht{constructor(){super(...arguments),this.size="md",this.disabled=!1,this.fullWidth=!1,this.loading=!1,this.variant="fill",this.hasIconLeft=!1,this.hasIconRight=!1}render(){this.style.cssText=`\n    --local-width: ${this.fullWidth?"100%":"auto"};\n    --local-opacity-100: ${this.loading?0:1};\n    --local-opacity-000: ${this.loading?1:0};`;const t="md"===this.size?"paragraph-600":"small-600";return G`
      <button
        data-variant=${this.variant}
        data-icon-left=${this.hasIconLeft}
        data-icon-right=${this.hasIconRight}
        data-size=${this.size}
        ?disabled=${this.disabled||this.loading}
        ontouchstart
      >
        ${this.loadingTemplate()}
        <slot name="iconLeft" @slotchange=${()=>this.handleSlotLeftChange()}></slot>
        <wui-text variant=${t} color="inherit">
          <slot></slot>
        </wui-text>
        <slot name="iconRight" @slotchange=${()=>this.handleSlotRightChange()}></slot>
      </button>
    `}handleSlotLeftChange(){this.hasIconLeft=!0}handleSlotRightChange(){this.hasIconRight=!0}loadingTemplate(){return this.loading?G`<wui-loading-spinner color="fg-300"></wui-loading-spinner>`:G``}};Br.styles=[bt,vt,Nr],Rr([Ct()],Br.prototype,"size",void 0),Rr([Ct({type:Boolean})],Br.prototype,"disabled",void 0),Rr([Ct({type:Boolean})],Br.prototype,"fullWidth",void 0),Rr([Ct({type:Boolean})],Br.prototype,"loading",void 0),Rr([Ct()],Br.prototype,"variant",void 0),Rr([Ct({type:Boolean})],Br.prototype,"hasIconLeft",void 0),Rr([Ct({type:Boolean})],Br.prototype,"hasIconRight",void 0),Br=Rr([xt("wui-button")],Br);const jr=K`<svg  viewBox="0 0 48 54" fill="none">
  <path
    d="M43.4605 10.7248L28.0485 1.61089C25.5438 0.129705 22.4562 0.129705 19.9515 1.61088L4.53951 10.7248C2.03626 12.2051 0.5 14.9365 0.5 17.886V36.1139C0.5 39.0635 2.03626 41.7949 4.53951 43.2752L19.9515 52.3891C22.4562 53.8703 25.5438 53.8703 28.0485 52.3891L43.4605 43.2752C45.9637 41.7949 47.5 39.0635 47.5 36.114V17.8861C47.5 14.9365 45.9637 12.2051 43.4605 10.7248Z"
  />
</svg>`,Lr=l`
  :host {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 76px;
    row-gap: var(--wui-spacing-xs);
    padding: var(--wui-spacing-xs) 10px;
    background-color: var(--wui-gray-glass-002);
    border-radius: clamp(0px, var(--wui-border-radius-xs), 20px);
    position: relative;
  }

  wui-shimmer[data-type='network'] {
    border: none;
    -webkit-clip-path: var(--wui-path-network);
    clip-path: var(--wui-path-network);
  }

  svg {
    position: absolute;
    width: 48px;
    height: 54px;
    z-index: 1;
  }

  svg > path {
    stroke: var(--wui-gray-glass-010);
    stroke-width: 1px;
  }
`;var Ur=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Dr=class extends ht{constructor(){super(...arguments),this.type="wallet"}render(){return G`
      ${this.shimmerTemplate()}
      <wui-shimmer width="56px" height="20px" borderRadius="xs"></wui-shimmer>
    `}shimmerTemplate(){return"network"===this.type?G` <wui-shimmer
          data-type=${this.type}
          width="48px"
          height="54px"
          borderRadius="xs"
        ></wui-shimmer>
        ${jr}`:G`<wui-shimmer width="56px" height="56px" borderRadius="xs"></wui-shimmer>`}};Dr.styles=[bt,vt,Lr],Ur([Ct()],Dr.prototype,"type",void 0),Dr=Ur([xt("wui-card-select-loader")],Dr);const $r=K`<svg width="86" height="96" fill="none">
  <path
    d="M78.3244 18.926L50.1808 2.45078C45.7376 -0.150261 40.2624 -0.150262 35.8192 2.45078L7.6756 18.926C3.23322 21.5266 0.5 26.3301 0.5 31.5248V64.4752C0.5 69.6699 3.23322 74.4734 7.6756 77.074L35.8192 93.5492C40.2624 96.1503 45.7376 96.1503 50.1808 93.5492L78.3244 77.074C82.7668 74.4734 85.5 69.6699 85.5 64.4752V31.5248C85.5 26.3301 82.7668 21.5266 78.3244 18.926Z"
  />
</svg>`,Fr=l`
  :host {
    position: relative;
    border-radius: inherit;
    display: flex;
    justify-content: center;
    align-items: center;
    width: var(--local-width);
    height: var(--local-height);
  }

  svg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
    fill: var(--wui-gray-glass-002);
  }

  svg > path {
    stroke: var(--local-stroke);
    transition: stroke var(--wui-ease-out-power-1) var(--wui-duration-lg);
  }

  wui-image {
    width: 100%;
    height: 100%;
    -webkit-clip-path: var(--local-path);
    clip-path: var(--local-path);
    background: var(--wui-gray-glass-002);
  }

  wui-icon {
    transform: translateY(-5%);
    width: var(--local-icon-size);
    height: var(--local-icon-size);
  }
`;var zr=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Hr=class extends ht{constructor(){super(...arguments),this.size="md",this.name="uknown",this.selected=!1}render(){const t="lg"===this.size;return this.style.cssText=`\n      --local-stroke: ${this.selected?"var(--wui-color-accent-100)":"var(--wui-gray-glass-010)"};\n      --local-path: ${t?"var(--wui-path-network-lg)":"var(--wui-path-network)"};\n      --local-width: ${t?"86px":"48px"};\n      --local-height: ${t?"96px":"54px"};\n      --local-icon-size: ${t?"42px":"24px"};\n    `,G`${this.templateVisual()} ${t?$r:jr}`}templateVisual(){return this.imageSrc?G`<wui-image src=${this.imageSrc} alt=${this.name}></wui-image>`:G`<wui-icon size="inherit" color="fg-200" name="networkPlaceholder"></wui-icon>`}};Hr.styles=[bt,Fr],zr([Ct()],Hr.prototype,"size",void 0),zr([Ct()],Hr.prototype,"name",void 0),zr([Ct()],Hr.prototype,"imageSrc",void 0),zr([Ct({type:Boolean})],Hr.prototype,"selected",void 0),Hr=zr([xt("wui-network-image")],Hr);const Wr=l`
  button {
    flex-direction: column;
    width: 76px;
    row-gap: var(--wui-spacing-xs);
    padding: var(--wui-spacing-xs) var(--wui-spacing-0);
    background-color: var(--wui-gray-glass-002);
    border-radius: clamp(0px, var(--wui-border-radius-xs), 20px);
  }

  button > wui-text {
    color: var(--wui-color-fg-100);
    max-width: var(--wui-icon-box-size-xl);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    justify-content: center;
  }

  button:disabled > wui-text {
    color: var(--wui-gray-glass-015);
  }

  [data-selected='true'] {
    background-color: var(--wui-accent-glass-020);
  }

  @media (hover: hover) and (pointer: fine) {
    [data-selected='true']:hover:enabled {
      background-color: var(--wui-accent-glass-015);
    }
  }

  [data-selected='true']:active:enabled {
    background-color: var(--wui-accent-glass-010);
  }
`;var qr=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Vr=class extends ht{constructor(){super(...arguments),this.name="Unknown",this.type="wallet",this.imageSrc=void 0,this.disabled=!1,this.selected=!1,this.installed=!1}render(){return G`
      <button data-selected=${pr(this.selected)} ?disabled=${this.disabled} ontouchstart>
        ${this.imageTemplate()}
        <wui-text variant="tiny-500" color=${this.selected?"accent-100":"inherit"}>
          ${this.name}
        </wui-text>
      </button>
    `}imageTemplate(){return"network"===this.type?G`
        <wui-network-image
          .selected=${this.selected}
          imageSrc=${pr(this.imageSrc)}
          name=${this.name}
        >
        </wui-network-image>
      `:G`
      <wui-wallet-image
        size="md"
        imageSrc=${pr(this.imageSrc)}
        name=${this.name}
        .installed=${this.installed}
        badgeSize="sm"
      >
      </wui-wallet-image>
    `}};Vr.styles=[bt,vt,Wr],qr([Ct()],Vr.prototype,"name",void 0),qr([Ct()],Vr.prototype,"type",void 0),qr([Ct()],Vr.prototype,"imageSrc",void 0),qr([Ct({type:Boolean})],Vr.prototype,"disabled",void 0),qr([Ct({type:Boolean})],Vr.prototype,"selected",void 0),qr([Ct({type:Boolean})],Vr.prototype,"installed",void 0),Vr=qr([xt("wui-card-select")],Vr);const Gr=l`
  a {
    border: 1px solid var(--wui-gray-glass-010);
    border-radius: var(--wui-border-radius-3xl);
  }

  wui-image {
    border-radius: var(--wui-border-radius-3xl);
    overflow: hidden;
  }

  a.disabled > wui-icon,
  a.disabled > wui-image {
    filter: grayscale(1);
  }

  a[data-variant='fill'] {
    color: var(--wui-color-inverse-100);
    background-color: var(--wui-color-accent-100);
  }

  a[data-variant='shade'],
  a[data-variant='shadeSmall'] {
    background-color: transparent;
    background-color: var(--wui-gray-glass-010);
    color: var(--wui-color-fg-200);
  }

  a[data-variant='success'] {
    column-gap: var(--wui-spacing-xxs);
    border: 1px solid var(--wui-success-glass-010);
    background-color: var(--wui-success-glass-010);
    color: var(--wui-color-success-100);
  }

  a[data-variant='transparent'] {
    column-gap: var(--wui-spacing-xxs);
    background-color: transparent;
    color: var(--wui-color-fg-150);
  }

  a[data-variant='transparent'],
  a[data-variant='success'],
  a[data-variant='shadeSmall'] {
    padding: 7px var(--wui-spacing-s) 7px 10px;
  }

  a[data-variant='transparent']:has(wui-text:first-child),
  a[data-variant='success']:has(wui-text:first-child),
  a[data-variant='shadeSmall']:has(wui-text:first-child) {
    padding: 7px var(--wui-spacing-s);
  }

  a[data-variant='fill'],
  a[data-variant='shade'] {
    column-gap: var(--wui-spacing-xs);
    padding: var(--wui-spacing-xxs) var(--wui-spacing-m) var(--wui-spacing-xxs)
      var(--wui-spacing-xs);
  }

  a[data-variant='fill']:has(wui-text:first-child),
  a[data-variant='shade']:has(wui-text:first-child) {
    padding: 9px var(--wui-spacing-m) 9px var(--wui-spacing-m);
  }

  a[data-variant='fill'] > wui-image,
  a[data-variant='shade'] > wui-image {
    width: 24px;
    height: 24px;
  }

  a[data-variant='fill'] > wui-image {
    box-shadow: inset 0 0 0 1px var(--wui-color-accent-090);
  }

  a[data-variant='shade'] > wui-image,
  a[data-variant='shadeSmall'] > wui-image {
    box-shadow: inset 0 0 0 1px var(--wui-gray-glass-010);
  }

  a[data-variant='fill'] > wui-icon,
  a[data-variant='shade'] > wui-icon {
    width: 14px;
    height: 14px;
  }

  a[data-variant='transparent'] > wui-image,
  a[data-variant='success'] > wui-image,
  a[data-variant='shadeSmall'] > wui-image {
    width: 14px;
    height: 14px;
  }

  a[data-variant='transparent'] > wui-icon,
  a[data-variant='success'] > wui-icon,
  a[data-variant='shadeSmall'] > wui-icon {
    width: 12px;
    height: 12px;
  }

  a[data-variant='fill']:focus-visible {
    background-color: var(--wui-color-accent-090);
  }

  a[data-variant='shade']:focus-visible,
  a[data-variant='shadeSmall']:focus-visible {
    background-color: var(--wui-gray-glass-015);
  }

  a[data-variant='transparent']:focus-visible {
    background-color: var(--wui-gray-glass-005);
  }

  a[data-variant='success']:focus-visible {
    background-color: var(--wui-success-glass-015);
  }

  a.disabled {
    color: var(--wui-gray-glass-015);
    background-color: var(--wui-gray-glass-015);
    pointer-events: none;
  }

  @media (hover: hover) and (pointer: fine) {
    a[data-variant='fill']:hover {
      background-color: var(--wui-color-accent-090);
    }

    a[data-variant='shade']:hover,
    a[data-variant='shadeSmall']:hover {
      background-color: var(--wui-gray-glass-015);
    }

    a[data-variant='transparent']:hover {
      background-color: var(--wui-gray-glass-005);
    }

    a[data-variant='success']:hover {
      background-color: var(--wui-success-glass-015);
    }
  }

  a[data-variant='fill']:active {
    background-color: var(--wui-color-accent-080);
  }

  a[data-variant='shade']:active,
  a[data-variant='shadeSmall']:active {
    background-color: var(--wui-gray-glass-020);
  }

  a[data-variant='transparent']:active {
    background-color: var(--wui-gray-glass-010);
  }

  a[data-variant='success']:active {
    background-color: var(--wui-success-glass-020);
  }
`;var Kr=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Zr=class extends ht{constructor(){super(...arguments),this.variant="fill",this.imageSrc=void 0,this.disabled=!1,this.icon="externalLink",this.href="",this.text=void 0}render(){const t="success"===this.variant||"transparent"===this.variant||"shadeSmall"===this.variant?"small-600":"paragraph-600";return G`
      <a
        rel="noreferrer"
        target="_blank"
        href=${this.href}
        class=${this.disabled?"disabled":""}
        data-variant=${this.variant}
      >
        ${this.imageTemplate()}
        <wui-text variant=${t} color="inherit">
          ${this.title?this.title:fr.getHostName(this.href)}
        </wui-text>
        <wui-icon name=${this.icon} color="inherit" size="inherit"></wui-icon>
      </a>
    `}imageTemplate(){return this.imageSrc?G`<wui-image src=${this.imageSrc}></wui-image>`:null}};Zr.styles=[bt,vt,Gr],Kr([Ct()],Zr.prototype,"variant",void 0),Kr([Ct()],Zr.prototype,"imageSrc",void 0),Kr([Ct({type:Boolean})],Zr.prototype,"disabled",void 0),Kr([Ct()],Zr.prototype,"icon",void 0),Kr([Ct()],Zr.prototype,"href",void 0),Kr([Ct()],Zr.prototype,"text",void 0),Zr=Kr([xt("wui-chip")],Zr);const Jr=l`
  :host {
    position: relative;
    display: block;
  }

  button {
    background: var(--wui-color-accent-100);
    border: 1px solid var(--wui-gray-glass-010);
    border-radius: var(--wui-border-radius-m);
    gap: var(--wui-spacing-xs);
  }

  button.loading {
    background: var(--wui-gray-glass-010);
    border: 1px solid var(--wui-gray-glass-010);
    pointer-events: none;
  }

  button:disabled {
    background-color: var(--wui-gray-glass-015);
    border: 1px solid var(--wui-gray-glass-010);
  }

  button:disabled > wui-text {
    color: var(--wui-gray-glass-015);
  }

  @media (hover: hover) and (pointer: fine) {
    button:hover:enabled {
      background-color: var(--wui-color-accent-090);
    }

    button:active:enabled {
      background-color: var(--wui-color-accent-080);
    }
  }

  button:focus-visible {
    border: 1px solid var(--wui-gray-glass-010);
    background-color: var(--wui-color-accent-090);
    -webkit-box-shadow: 0px 0px 0px 4px var(--wui-box-shadow-blue);
    -moz-box-shadow: 0px 0px 0px 4px var(--wui-box-shadow-blue);
    box-shadow: 0px 0px 0px 4px var(--wui-box-shadow-blue);
  }

  button[data-size='sm'] {
    padding: 6.75px 10px 7.25px;
  }

  ::slotted(*) {
    transition: opacity 200ms ease-in-out;
    opacity: var(--local-opacity-100);
  }

  button > wui-text {
    transition: opacity 200ms ease-in-out;
    opacity: var(--local-opacity-100);
    color: var(--wui-color-inverse-100);
  }

  button[data-size='md'] {
    padding: 9px var(--wui-spacing-l) 9px var(--wui-spacing-l);
  }

  button[data-size='md'] + wui-text {
    padding-left: var(--wui-spacing-3xs);
  }

  @media (max-width: 500px) {
    button[data-size='md'] {
      height: 32px;
      padding: 5px 12px;
    }

    button[data-size='md'] > wui-text > slot {
      font-size: 14px !important;
    }
  }

  wui-loading-spinner {
    width: 14px;
    height: 14px;
  }

  wui-loading-spinner::slotted(svg) {
    width: 10px !important;
    height: 10px !important;
  }

  button[data-size='sm'] > wui-loading-spinner {
    width: 12px;
    height: 12px;
  }
`;var Qr=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Yr=class extends ht{constructor(){super(...arguments),this.size="md",this.loading=!1}render(){const t="md"===this.size?"paragraph-600":"small-600";return G`
      <button data-size=${this.size} ?disabled=${this.loading} ontouchstart>
        ${this.loadingTemplate()}
        <wui-text variant=${t} color=${this.loading?"accent-100":"inherit"}>
          <slot></slot>
        </wui-text>
      </button>
    `}loadingTemplate(){return this.loading?G`<wui-loading-spinner size=${this.size} color="accent-100"></wui-loading-spinner>`:null}};Yr.styles=[bt,vt,Jr],Qr([Ct()],Yr.prototype,"size",void 0),Qr([Ct({type:Boolean})],Yr.prototype,"loading",void 0),Yr=Qr([xt("wui-connect-button")],Yr);const Xr=l`
  wui-flex {
    width: 100%;
    background-color: var(--wui-gray-glass-002);
    border-radius: var(--wui-border-radius-xs);
  }
`;var tn=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let en=class extends ht{constructor(){super(...arguments),this.disabled=!1,this.label="",this.buttonLabel=""}render(){return G`
      <wui-flex
        justifyContent="space-between"
        alignItems="center"
        .padding=${["1xs","2l","1xs","2l"]}
      >
        <wui-text variant="paragraph-500" color="fg-200">${this.label}</wui-text>
        <wui-button size="sm" variant="accent">
          ${this.buttonLabel}
          <wui-icon size="xs" color="inherit" slot="iconRight" name="chevronRight"></wui-icon>
        </wui-button>
      </wui-flex>
    `}};en.styles=[bt,vt,Xr],tn([Ct({type:Boolean})],en.prototype,"disabled",void 0),tn([Ct()],en.prototype,"label",void 0),tn([Ct()],en.prototype,"buttonLabel",void 0),en=tn([xt("wui-cta-button")],en);const{D:rn}=ut,nn=(t,e)=>{const r=t._$AN;if(void 0===r)return!1;for(const t of r)t._$AO?.(e,!1),nn(t,e);return!0},sn=t=>{let e,r;do{if(void 0===(e=t._$AM))break;r=e._$AN,r.delete(t),t=e}while(0===r?.size)},on=t=>{for(let e;e=t._$AM;t=e){let r=e._$AN;if(void 0===r)e._$AN=r=new Set;else if(r.has(t))break;r.add(t),ln(e)}};function an(t){void 0!==this._$AN?(sn(this),this._$AM=t,on(this)):this._$AM=t}function cn(t,e=!1,r=0){const n=this._$AH,i=this._$AN;if(void 0!==i&&0!==i.size)if(e)if(Array.isArray(n))for(let t=r;t<n.length;t++)nn(n[t],!1),sn(n[t]);else null!=n&&(nn(n,!1),sn(n));else nn(this,t)}const ln=t=>{2==t.type&&(t._$AP??=cn,t._$AQ??=an)};class un extends qe{constructor(){super(...arguments),this._$AN=void 0}_$AT(t,e,r){super._$AT(t,e,r),on(this),this.isConnected=t._$AU}_$AO(t,e=!0){t!==this.isConnected&&(this.isConnected=t,t?this.reconnected?.():this.disconnected?.()),e&&(nn(this,t),sn(this))}setValue(t){if((t=>void 0===this._$Ct.strings)())this._$Ct._$AI(t,this);else{const e=[...this._$Ct._$AH];e[this._$Ci]=t,this._$Ct._$AI(e,this,0)}}disconnected(){}reconnected(){}}const hn=()=>new dn;class dn{}const pn=new WeakMap,fn=We(class extends un{render(t){return J}update(t,[e]){const r=e!==this.G;return r&&void 0!==this.G&&this.ot(void 0),(r||this.rt!==this.lt)&&(this.G=e,this.ct=t.options?.host,this.ot(this.lt=t.element)),J}ot(t){if("function"==typeof this.G){const e=this.ct??globalThis;let r=pn.get(e);void 0===r&&(r=new WeakMap,pn.set(e,r)),void 0!==r.get(this.G)&&this.G.call(this.ct,void 0),r.set(this.G,t),void 0!==t&&this.G.call(this.ct,t)}else this.G.value=t}get rt(){return"function"==typeof this.G?pn.get(this.ct??globalThis)?.get(this.G):this.G?.value}disconnected(){this.rt===this.lt&&this.ot(void 0)}reconnected(){this.ot(this.lt)}}),mn=l`
  :host {
    position: relative;
    width: 100%;
    display: inline-block;
    color: var(--wui-color-fg-275);
  }

  input {
    width: 100%;
    border-radius: var(--wui-border-radius-xs);
    border: 1px solid var(--wui-gray-glass-005);
    background: var(--wui-gray-glass-005);
    font-size: var(--wui-font-size-paragraph);
    font-weight: var(--wui-font-weight-light);
    letter-spacing: var(--wui-letter-spacing-paragraph);
    color: var(--wui-color-fg-100);
    transition: all var(--wui-ease-inout-power-1) var(--wui-duration-lg);
    caret-color: var(--wui-color-accent-100);
  }

  input:disabled {
    cursor: not-allowed;
    border: 1px solid var(--wui-gray-glass-010);
    background: var(--wui-gray-glass-015);
  }

  input:disabled::placeholder,
  input:disabled + wui-icon {
    color: var(--wui-color-fg-300);
  }

  input::placeholder {
    color: var(--wui-color-fg-275);
  }

  input:focus:enabled {
    transition: all var(--wui-ease-out-power-2) var(--wui-duration-sm);
    background-color: var(--wui-gray-glass-010);
    border: 1px solid var(--wui-color-accent-100);
    -webkit-box-shadow: 0px 0px 0px 4px var(--wui-box-shadow-blue);
    -moz-box-shadow: 0px 0px 0px 4px var(--wui-box-shadow-blue);
    box-shadow: 0px 0px 0px 4px var(--wui-box-shadow-blue);
  }

  input:hover:enabled {
    background-color: var(--wui-gray-glass-010);
  }

  wui-icon {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
  }

  .wui-size-sm {
    padding: 9px var(--wui-spacing-m) 10px var(--wui-spacing-s);
  }

  wui-icon + .wui-size-sm {
    padding: 9px var(--wui-spacing-m) 10px 36px;
  }

  wui-icon[data-input='sm'] {
    left: var(--wui-spacing-s);
  }

  .wui-size-md {
    padding: 15px var(--wui-spacing-m) var(--wui-spacing-l) var(--wui-spacing-m);
  }

  wui-icon + .wui-size-md {
    padding: 10.5px var(--wui-spacing-l) 10.5px 44px;
  }

  wui-icon[data-input='md'] {
    left: var(--wui-spacing-l);
  }

  input:placeholder-shown ~ ::slotted(wui-input-element),
  input:placeholder-shown ~ ::slotted(wui-icon) {
    opacity: 0;
    pointer-events: none;
  }

  ::slotted(wui-input-element),
  ::slotted(wui-icon) {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    transition: all var(--wui-ease-in-power-2) var(--wui-duration-md);
  }

  ::slotted(wui-input-element) {
    right: var(--wui-spacing-m);
  }

  ::slotted(wui-icon) {
    right: 0px;
  }
`;var gn=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let yn=class extends ht{constructor(){super(...arguments),this.inputElementRef=hn(),this.size="md",this.disabled=!1,this.placeholder="",this.type="text"}render(){const t=`wui-size-${this.size}`;return G` ${this.templateIcon()}
      <input
        ${fn(this.inputElementRef)}
        class=${t}
        type=${this.type}
        enterkeyhint=${pr(this.enterKeyHint)}
        ?disabled=${this.disabled}
        placeholder=${this.placeholder}
        @input=${this.dispatchInputChangeEvent.bind(this)}
        value=${pr(this.value)}
      />
      <slot></slot>`}templateIcon(){return this.icon?G`<wui-icon
        data-input=${this.size}
        size="sm"
        color="inherit"
        name=${this.icon}
      ></wui-icon>`:null}dispatchInputChangeEvent(){this.dispatchEvent(new CustomEvent("inputChange",{detail:this.inputElementRef.value?.value,bubbles:!0,composed:!0}))}};yn.styles=[bt,vt,mn],gn([Ct()],yn.prototype,"size",void 0),gn([Ct()],yn.prototype,"icon",void 0),gn([Ct({type:Boolean})],yn.prototype,"disabled",void 0),gn([Ct()],yn.prototype,"placeholder",void 0),gn([Ct()],yn.prototype,"type",void 0),gn([Ct()],yn.prototype,"keyHint",void 0),gn([Ct()],yn.prototype,"value",void 0),yn=gn([xt("wui-input-text")],yn);const wn=l`
  :host {
    position: relative;
    display: inline-block;
  }

  wui-text {
    margin: var(--wui-spacing-xxs) var(--wui-spacing-m) var(--wui-spacing-0) var(--wui-spacing-m);
  }
`;var bn=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let vn=class extends ht{constructor(){super(...arguments),this.disabled=!1}render(){return G`
      <wui-input-text
        placeholder="Email"
        icon="mail"
        size="md"
        .disabled=${this.disabled}
        .value=${this.value}
        data-testid="wui-email-input"
      ></wui-input-text>
      ${this.templateError()}
    `}templateError(){return this.errorMessage?G`<wui-text variant="tiny-500" color="error-100">${this.errorMessage}</wui-text>`:null}};vn.styles=[bt,wn],bn([Ct()],vn.prototype,"errorMessage",void 0),bn([Ct({type:Boolean})],vn.prototype,"disabled",void 0),bn([Ct()],vn.prototype,"value",void 0),vn=bn([xt("wui-email-input")],vn);const En=l`
  button {
    border-radius: var(--wui-border-radius-xxs);
    color: var(--wui-color-fg-100);
    padding: var(--wui-spacing-2xs);
  }

  @media (max-width: 700px) {
    button {
      padding: var(--wui-spacing-s);
    }
  }

  button > wui-icon {
    pointer-events: none;
  }

  button:disabled > wui-icon {
    color: var(--wui-color-bg-300) !important;
  }

  button:disabled {
    background-color: transparent;
  }
`;var xn=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let _n=class extends ht{constructor(){super(...arguments),this.size="md",this.disabled=!1,this.icon="copy",this.iconColor="inherit"}render(){return G`
      <button ?disabled=${this.disabled} ontouchstart>
        <wui-icon color=${this.iconColor} size=${this.size} name=${this.icon}></wui-icon>
      </button>
    `}};_n.styles=[bt,vt,Et,En],xn([Ct()],_n.prototype,"size",void 0),xn([Ct({type:Boolean})],_n.prototype,"disabled",void 0),xn([Ct()],_n.prototype,"icon",void 0),xn([Ct()],_n.prototype,"iconColor",void 0),_n=xn([xt("wui-icon-link")],_n);const An=l`
  button {
    background-color: var(--wui-color-fg-300);
    border-radius: var(--wui-border-radius-4xs);
    width: 16px;
    height: 16px;
  }

  button:disabled {
    background-color: var(--wui-color-bg-300);
  }

  wui-icon {
    color: var(--wui-color-bg-200) !important;
  }

  button:focus-visible {
    background-color: var(--wui-color-fg-250);
    border: 1px solid var(--wui-color-accent-100);
  }

  button:active:enabled {
    background-color: var(--wui-color-fg-225);
  }

  @media (hover: hover) and (pointer: fine) {
    button:hover:enabled {
      background-color: var(--wui-color-fg-250);
    }
  }
`;var kn=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Sn=class extends ht{constructor(){super(...arguments),this.icon="copy"}render(){return G`
      <button>
        <wui-icon color="inherit" size="xxs" name=${this.icon}></wui-icon>
      </button>
    `}};Sn.styles=[bt,vt,An],kn([Ct()],Sn.prototype,"icon",void 0),Sn=kn([xt("wui-input-element")],Sn);const Cn=l`
  :host {
    position: relative;
    display: inline-block;
  }

  input {
    width: 50px;
    height: 50px;
    background: var(--wui-gray-glass-005);
    border-radius: var(--wui-border-radius-xs);
    border: 1px solid var(--wui-gray-glass-005);
    font-family: var(--wui-font-family);
    font-size: var(--wui-font-size-large);
    font-weight: var(--wui-font-weight-regular);
    letter-spacing: var(--wui-letter-spacing-large);
    text-align: center;
    color: var(--wui-color-fg-100);
    caret-color: var(--wui-color-accent-100);
    transition: all var(--wui-ease-inout-power-1) var(--wui-duration-lg);
    box-sizing: border-box;
    -webkit-appearance: none;
    -moz-appearance: textfield;
    padding: 0px;
  }

  input::-webkit-outer-spin-button,
  input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  input[type='number'] {
    -moz-appearance: textfield;
  }

  input:disabled {
    cursor: not-allowed;
    border: 1px solid var(--wui-gray-glass-010);
    background: var(--wui-gray-glass-015);
  }

  input:focus:enabled {
    transition: all var(--wui-ease-out-power-2) var(--wui-duration-sm);
    background-color: var(--wui-gray-glass-010);
    border: 1px solid var(--wui-color-accent-100);
    -webkit-box-shadow: 0px 0px 0px 4px var(--wui-box-shadow-blue);
    -moz-box-shadow: 0px 0px 0px 4px var(--wui-box-shadow-blue);
    box-shadow: 0px 0px 0px 4px var(--wui-box-shadow-blue);
  }
  @media (hover: hover) and (pointer: fine) {
    input:hover:enabled {
      background-color: var(--wui-gray-glass-010);
    }
  }
`;var Mn=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let In=class extends ht{constructor(){super(...arguments),this.disabled=!1}render(){return G`<input
      type="number"
      maxlength="1"
      inputmode="numeric"
      autofocus
      ?disabled=${this.disabled}
    /> `}};In.styles=[bt,vt,Cn],Mn([Ct({type:Boolean})],In.prototype,"disabled",void 0),In=Mn([xt("wui-input-numeric")],In);const Pn=l`
  button {
    padding: var(--wui-spacing-4xs) var(--wui-spacing-xxs);
    border-radius: var(--wui-border-radius-3xs);
    background-color: transparent;
    color: var(--wui-color-accent-100);
  }

  button:disabled {
    background-color: transparent;
    color: var(--wui-gray-glass-015);
  }
`;var On=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Tn=class extends ht{constructor(){super(...arguments),this.disabled=!1,this.color="inherit"}render(){return G`
      <button ?disabled=${this.disabled} ontouchstart>
        <slot name="iconLeft"></slot>
        <wui-text variant="small-600" color=${this.color}>
          <slot></slot>
        </wui-text>
        <slot name="iconRight"></slot>
      </button>
    `}};Tn.styles=[bt,vt,Pn],On([Ct({type:Boolean})],Tn.prototype,"disabled",void 0),On([Ct()],Tn.prototype,"color",void 0),Tn=On([xt("wui-link")],Tn);const Nn=l`
  button {
    column-gap: var(--wui-spacing-s);
    padding: 11px 18px 11px var(--wui-spacing-s);
    width: 100%;
    background-color: var(--wui-gray-glass-002);
    border-radius: var(--wui-border-radius-xs);
    color: var(--wui-color-fg-250);
  }

  button[data-iconvariant='square'],
  button[data-iconvariant='square-blue'] {
    padding: 6px 18px 6px 9px;
  }

  button > wui-flex {
    flex: 1;
  }

  button > wui-image {
    width: 32px;
    height: 32px;
    box-shadow: 0 0 0 2px var(--wui-gray-glass-005);
    border-radius: var(--wui-border-radius-3xl);
  }

  button > wui-icon {
    width: 36px;
    height: 36px;
  }

  button > wui-icon-box[data-variant='blue'] {
    box-shadow: 0 0 0 2px var(--wui-accent-glass-005);
  }

  button > wui-icon-box[data-variant='overlay'] {
    box-shadow: 0 0 0 2px var(--wui-gray-glass-005);
  }

  button > wui-icon-box[data-variant='square-blue'] {
    border-radius: var(--wui-border-radius-3xs);
    position: relative;
    border: none;
    width: 36px;
    height: 36px;
  }

  button > wui-icon-box[data-variant='square-blue']::after {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    border-radius: inherit;
    border: 1px solid var(--wui-accent-glass-010);
    pointer-events: none;
  }

  button > wui-icon:last-child {
    width: 14px;
    height: 14px;
  }

  button:disabled {
    background-color: var(--wui-gray-glass-015);
    color: var(--wui-gray-glass-015);
  }

  button[data-loading='true'] > wui-icon {
    transition: opacity 200ms ease-in-out;
    opacity: 0;
  }

  wui-loading-spinner {
    position: absolute;
    right: 18px;
    top: 50%;
    transform: translateY(-50%);
  }
`;var Rn=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Bn=class extends ht{constructor(){super(...arguments),this.variant="icon",this.disabled=!1,this.imageSrc=void 0,this.alt=void 0,this.chevron=!1,this.loading=!1}render(){return G`
      <button
        ?disabled=${!!this.loading||Boolean(this.disabled)}
        data-loading=${this.loading}
        data-iconvariant=${pr(this.iconVariant)}
        ontouchstart
      >
        ${this.loadingTemplate()} ${this.visualTemplate()}
        <wui-flex gap="3xs">
          <slot></slot>
        </wui-flex>
        ${this.chevronTemplate()}
      </button>
    `}visualTemplate(){if("image"===this.variant&&this.imageSrc)return G`<wui-image src=${this.imageSrc} alt=${this.alt??"list item"}></wui-image>`;if("square"===this.iconVariant&&this.icon&&"icon"===this.variant)return G`<wui-icon name=${this.icon}></wui-icon>`;if("icon"===this.variant&&this.icon&&this.iconVariant){const t=["blue","square-blue"].includes(this.iconVariant)?"accent-100":"fg-200",e="square-blue"===this.iconVariant?"mdl":"md",r=this.iconSize?this.iconSize:e;return G`
        <wui-icon-box
          data-variant=${this.iconVariant}
          icon=${this.icon}
          iconSize=${r}
          background="transparent"
          iconColor=${t}
          backgroundColor=${t}
          size=${e}
        ></wui-icon-box>
      `}return null}loadingTemplate(){return this.loading?G`<wui-loading-spinner color="fg-300"></wui-loading-spinner>`:G``}chevronTemplate(){return this.chevron?G`<wui-icon size="inherit" color="fg-200" name="chevronRight"></wui-icon>`:null}};var jn;Bn.styles=[bt,vt,Nn],Rn([Ct()],Bn.prototype,"icon",void 0),Rn([Ct()],Bn.prototype,"iconSize",void 0),Rn([Ct()],Bn.prototype,"variant",void 0),Rn([Ct()],Bn.prototype,"iconVariant",void 0),Rn([Ct({type:Boolean})],Bn.prototype,"disabled",void 0),Rn([Ct()],Bn.prototype,"imageSrc",void 0),Rn([Ct()],Bn.prototype,"alt",void 0),Rn([Ct({type:Boolean})],Bn.prototype,"chevron",void 0),Rn([Ct({type:Boolean})],Bn.prototype,"loading",void 0),Bn=Rn([xt("wui-list-item")],Bn),function(t){t.approve="approved",t.bought="bought",t.borrow="borrowed",t.burn="burnt",t.cancel="canceled",t.claim="claimed",t.deploy="deployed",t.deposit="deposited",t.execute="executed",t.mint="minted",t.receive="received",t.repay="repaid",t.send="sent",t.sell="sold",t.stake="staked",t.trade="swapped",t.unstake="unstaked",t.withdraw="withdrawn"}(jn||(jn={}));const Ln=l`
  :host > wui-flex {
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    width: 40px;
    height: 40px;
    box-shadow: inset 0 0 0 1px var(--wui-gray-glass-005);
    background-color: var(--wui-gray-glass-005);
  }

  :host > wui-flex wui-image {
    display: block;
    z-index: -1;
  }

  :host > wui-flex,
  :host > wui-flex wui-image,
  .swap-images-container,
  .swap-images-container.nft,
  wui-image.nft {
    border-top-left-radius: var(--local-left-border-radius);
    border-top-right-radius: var(--local-right-border-radius);
    border-bottom-left-radius: var(--local-left-border-radius);
    border-bottom-right-radius: var(--local-right-border-radius);
  }

  wui-icon {
    width: 20px;
    height: 20px;
  }

  wui-icon-box {
    position: absolute;
    right: 0;
    bottom: 0;
    transform: translate(20%, 20%);
  }

  .swap-images-container {
    position: relative;
    width: 40px;
    height: 40px;
    overflow: hidden;
  }

  .swap-images-container wui-image:first-child {
    position: absolute;
    width: 40px;
    height: 40px;
    top: 0;
    left: 0%;
    clip-path: inset(0px calc(50% + 2px) 0px 0%);
  }

  .swap-images-container wui-image:last-child {
    clip-path: inset(0px 0px 0px calc(50% + 2px));
  }
`;var Un=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Dn=class extends ht{constructor(){super(...arguments),this.images=[],this.secondImage={type:void 0,url:""}}render(){const[t,e]=this.images,r="NFT"===t?.type,n=r?"var(--wui-border-radius-xxs)":"var(--wui-border-radius-s)",i=(e?.url?"NFT"===e.type:r)?"var(--wui-border-radius-xxs)":"var(--wui-border-radius-s)";return this.style.cssText=`\n    --local-left-border-radius: ${n};\n    --local-right-border-radius: ${i};\n    `,G`<wui-flex> ${this.templateVisual()} ${this.templateIcon()} </wui-flex>`}templateVisual(){const[t,e]=this.images,r=t?.type;return 2===this.images.length&&(t?.url||e?.url)?G`<div class="swap-images-container">
        ${t?.url?G`<wui-image src=${t.url} alt="Transaction image"></wui-image>`:null}
        ${e?.url?G`<wui-image src=${e.url} alt="Transaction image"></wui-image>`:null}
      </div>`:t?.url?G`<wui-image src=${t.url} alt="Transaction image"></wui-image>`:"NFT"===r?G`<wui-icon size="inherit" color="fg-200" name="nftPlaceholder"></wui-icon>`:G`<wui-icon size="inherit" color="fg-200" name="coinPlaceholder"></wui-icon>`}templateIcon(){let t,e="accent-100";return t=this.getIcon(),this.status&&(e=this.getStatusColor()),t?G`
      <wui-icon-box
        size="xxs"
        iconColor=${e}
        backgroundColor=${e}
        background="opaque"
        icon=${t}
        ?border=${!0}
        borderColor="wui-color-bg-125"
      ></wui-icon-box>
    `:null}getDirectionIcon(){switch(this.direction){case"in":return"arrowBottom";case"out":return"arrowTop";default:return}}getIcon(){return this.onlyDirectionIcon?this.getDirectionIcon():"trade"===this.type?"swapHorizontalBold":"approve"===this.type?"checkmark":"cancel"===this.type?"close":this.getDirectionIcon()}getStatusColor(){switch(this.status){case"confirmed":return"success-100";case"failed":return"error-100";case"pending":return"inverse-100";default:return"accent-100"}}};Dn.styles=[Ln],Un([Ct()],Dn.prototype,"type",void 0),Un([Ct()],Dn.prototype,"status",void 0),Un([Ct()],Dn.prototype,"direction",void 0),Un([Ct({type:Boolean})],Dn.prototype,"onlyDirectionIcon",void 0),Un([Ct({type:Array})],Dn.prototype,"images",void 0),Un([Ct({type:Object})],Dn.prototype,"secondImage",void 0),Dn=Un([xt("wui-transaction-visual")],Dn);const $n=l`
  :host > wui-flex:first-child {
    align-items: center;
    column-gap: var(--wui-spacing-s);
    padding: 6.5px var(--wui-spacing-l) 6.5px var(--wui-spacing-xs);
    width: 100%;
  }

  :host > wui-flex:first-child wui-text:nth-child(1) {
    text-transform: capitalize;
  }

  wui-transaction-visual {
    width: 40px;
    height: 40px;
  }

  wui-flex {
    flex: 1;
  }

  :host wui-flex wui-flex {
    overflow: hidden;
  }

  :host .description-container wui-text span {
    word-break: break-all;
  }

  :host .description-container wui-text {
    overflow: hidden;
  }

  :host .description-separator-icon {
    margin: 0px 6px;
  }

  :host wui-text > span {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 1;
  }
`;var Fn=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let zn=class extends ht{constructor(){super(...arguments),this.type="approve",this.onlyDirectionIcon=!1,this.images=[]}render(){return G`
      <wui-flex>
        <wui-transaction-visual
          .status=${this.status}
          direction=${pr(this.direction)}
          type=${this.type}
          onlyDirectionIcon=${pr(this.onlyDirectionIcon)}
          .images=${this.images}
        ></wui-transaction-visual>
        <wui-flex flexDirection="column" gap="3xs">
          <wui-text variant="paragraph-600" color="fg-100">
            ${jn[this.type]}
          </wui-text>
          <wui-flex class="description-container">
            ${this.templateDescription()} ${this.templateSecondDescription()}
          </wui-flex>
        </wui-flex>
        <wui-text variant="micro-700" color="fg-300"><span>${this.date}</span></wui-text>
      </wui-flex>
    `}templateDescription(){const t=this.descriptions?.[0];return t?G`
          <wui-text variant="small-500" color="fg-200">
            <span>${t}</span>
          </wui-text>
        `:null}templateSecondDescription(){const t=this.descriptions?.[1];return t?G`
          <wui-icon class="description-separator-icon" size="xxs" name="arrowRight"></wui-icon>
          <wui-text variant="small-400" color="fg-200">
            <span>${t}</span>
          </wui-text>
        `:null}};zn.styles=[bt,$n],Fn([Ct()],zn.prototype,"type",void 0),Fn([Ct({type:Array})],zn.prototype,"descriptions",void 0),Fn([Ct()],zn.prototype,"date",void 0),Fn([Ct({type:Boolean})],zn.prototype,"onlyDirectionIcon",void 0),Fn([Ct()],zn.prototype,"status",void 0),Fn([Ct()],zn.prototype,"direction",void 0),Fn([Ct({type:Array})],zn.prototype,"images",void 0),zn=Fn([xt("wui-transaction-list-item")],zn);const Hn=l`
  :host > wui-flex:first-child {
    column-gap: var(--wui-spacing-s);
    padding: 7px var(--wui-spacing-l) 7px var(--wui-spacing-xs);
    width: 100%;
  }

  wui-flex {
    display: flex;
    flex: 1;
  }
`;let Wn=class extends ht{render(){return G`
      <wui-flex alignItems="center">
        <wui-shimmer width="40px" height="40px"></wui-shimmer>
        <wui-flex flexDirection="column" gap="2xs">
          <wui-shimmer width="72px" height="16px" borderRadius="4xs"></wui-shimmer>
          <wui-shimmer width="148px" height="14px" borderRadius="4xs"></wui-shimmer>
        </wui-flex>
        <wui-shimmer width="24px" height="12px" borderRadius="5xs"></wui-shimmer>
      </wui-flex>
    `}};Wn.styles=[bt,Hn],Wn=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o}([xt("wui-transaction-list-item-loader")],Wn);const qn=l`
  :host {
    display: block;
    padding: 3.5px 5px !important;
    border-radius: var(--wui-border-radius-5xs);
  }

  :host([data-variant='main']) {
    background-color: var(--wui-accent-glass-015);
    color: var(--wui-color-accent-100);
  }

  :host([data-variant='shade']) {
    background-color: var(--wui-gray-glass-010);
    color: var(--wui-color-fg-200);
  }

  :host([data-variant='success']) {
    background-color: var(--wui-icon-box-bg-success-100);
    color: var(--wui-color-success-100);
  }

  :host([data-variant='error']) {
    background-color: var(--wui-icon-box-bg-error-100);
    color: var(--wui-color-error-100);
  }
`;var Vn=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Gn=class extends ht{constructor(){super(...arguments),this.variant="main"}render(){return this.dataset.variant=this.variant,G`
      <wui-text data-variant=${this.variant} variant="micro-700" color="inherit">
        <slot></slot>
      </wui-text>
    `}};Gn.styles=[bt,qn],Vn([Ct()],Gn.prototype,"variant",void 0),Gn=Vn([xt("wui-tag")],Gn);const Kn=l`
  button {
    column-gap: var(--wui-spacing-s);
    padding: 7px var(--wui-spacing-l) 7px var(--wui-spacing-xs);
    width: 100%;
    background-color: var(--wui-gray-glass-002);
    border-radius: var(--wui-border-radius-xs);
    color: var(--wui-color-fg-100);
  }

  button > wui-text:nth-child(2) {
    display: flex;
    flex: 1;
  }

  wui-icon {
    color: var(--wui-color-fg-200) !important;
  }

  button:disabled {
    background-color: var(--wui-gray-glass-015);
    color: var(--wui-gray-glass-015);
  }

  button:disabled > wui-tag {
    background-color: var(--wui-gray-glass-010);
    color: var(--wui-color-fg-300);
  }
`;var Zn=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Jn=class extends ht{constructor(){super(...arguments),this.walletImages=[],this.imageSrc="",this.name="",this.installed=!1,this.disabled=!1,this.showAllWallets=!1}render(){return G`
      <button ?disabled=${this.disabled} ontouchstart>
        ${this.templateAllWallets()} ${this.templateWalletImage()}
        <wui-text variant="paragraph-500" color="inherit">${this.name}</wui-text>
        ${this.templateStatus()}
      </button>
    `}templateAllWallets(){return this.showAllWallets&&this.imageSrc?G` <wui-all-wallets-image .imageeSrc=${this.imageSrc}> </wui-all-wallets-image> `:this.showAllWallets&&this.walletIcon?G` <wui-wallet-image .walletIcon=${this.walletIcon} size="sm"> </wui-wallet-image> `:null}templateWalletImage(){return!this.showAllWallets&&this.imageSrc?G`<wui-wallet-image
        size="sm"
        imageSrc=${this.imageSrc}
        name=${this.name}
        .installed=${this.installed}
      ></wui-wallet-image>`:this.showAllWallets||this.imageSrc?null:G`<wui-wallet-image size="sm" name=${this.name}></wui-wallet-image>`}templateStatus(){return this.tagLabel&&this.tagVariant?G`<wui-tag variant=${this.tagVariant}>${this.tagLabel}</wui-tag>`:this.icon?G`<wui-icon color="inherit" size="sm" name=${this.icon}></wui-icon>`:null}};Jn.styles=[bt,vt,Kn],Zn([Ct({type:Array})],Jn.prototype,"walletImages",void 0),Zn([Ct()],Jn.prototype,"imageSrc",void 0),Zn([Ct()],Jn.prototype,"name",void 0),Zn([Ct()],Jn.prototype,"tagLabel",void 0),Zn([Ct()],Jn.prototype,"tagVariant",void 0),Zn([Ct()],Jn.prototype,"icon",void 0),Zn([Ct()],Jn.prototype,"walletIcon",void 0),Zn([Ct({type:Boolean})],Jn.prototype,"installed",void 0),Zn([Ct({type:Boolean})],Jn.prototype,"disabled",void 0),Zn([Ct({type:Boolean})],Jn.prototype,"showAllWallets",void 0),Jn=Zn([xt("wui-list-wallet")],Jn);const Qn=l`
  :host {
    display: block;
    width: 40px;
    height: 40px;
    border-radius: var(--wui-border-radius-3xl);
    border: 1px solid var(--wui-gray-glass-010);
    overflow: hidden;
  }

  wui-icon {
    width: 100%;
    height: 100%;
  }
`;var Yn=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Xn=class extends ht{constructor(){super(...arguments),this.logo="google"}render(){return G`<wui-icon color="inherit" size="inherit" name=${this.logo}></wui-icon> `}};Xn.styles=[bt,Qn],Yn([Ct()],Xn.prototype,"logo",void 0),Xn=Yn([xt("wui-logo")],Xn);const ti=l`
  :host {
    display: block;
  }

  button {
    width: 50px;
    height: 50px;
    background: var(--wui-gray-glass-002);
    border-radius: var(--wui-border-radius-xs);
  }
`;var ei=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let ri=class extends ht{constructor(){super(...arguments),this.logo="google",this.disabled=!1}render(){return G`
      <button ?disabled=${this.disabled} ontouchstart>
        <wui-logo logo=${this.logo}></wui-logo>
      </button>
    `}};ri.styles=[bt,vt,ti],ei([Ct()],ri.prototype,"logo",void 0),ei([Ct({type:Boolean})],ri.prototype,"disabled",void 0),ri=ei([xt("wui-logo-select")],ri);const ni=l`
  :host {
    display: block;
  }

  button {
    border-radius: var(--wui-border-radius-3xl);
    display: flex;
    gap: var(--wui-spacing-xs);
    padding: var(--wui-spacing-2xs) var(--wui-spacing-s) var(--wui-spacing-2xs)
      var(--wui-spacing-xs);
    border: 1px solid var(--wui-gray-glass-010);
    background-color: var(--wui-gray-glass-005);
    color: var(--wui-color-fg-100);
  }

  button:disabled {
    border: 1px solid var(--wui-gray-glass-005);
    background-color: var(--wui-gray-glass-015);
    color: var(--wui-gray-glass-015);
  }

  @media (hover: hover) and (pointer: fine) {
    button:hover:enabled {
      background-color: var(--wui-gray-glass-010);
    }

    button:active:enabled {
      background-color: var(--wui-gray-glass-015);
    }
  }

  wui-image,
  wui-icon-box {
    border-radius: var(--wui-border-radius-3xl);
    width: 24px;
    height: 24px;
    box-shadow: 0 0 0 2px var(--wui-gray-glass-005);
  }
`;var ii=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let si=class extends ht{constructor(){super(...arguments),this.imageSrc=void 0,this.disabled=!1}render(){return G`
      <button ?disabled=${this.disabled}>
        ${this.visualTemplate()}
        <wui-text variant="paragraph-600" color="inherit">
          <slot></slot>
        </wui-text>
      </button>
    `}visualTemplate(){return this.imageSrc?G`<wui-image src=${this.imageSrc}></wui-image>`:G`
      <wui-icon-box
        size="sm"
        iconColor="inverse-100"
        backgroundColor="fg-100"
        icon="networkPlaceholder"
      ></wui-icon-box>
    `}};si.styles=[bt,vt,ni],ii([Ct()],si.prototype,"imageSrc",void 0),ii([Ct({type:Boolean})],si.prototype,"disabled",void 0),si=ii([xt("wui-network-button")],si);const oi=l`
  :host {
    position: relative;
    display: block;
  }
`;var ai=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let ci=class extends ht{constructor(){super(...arguments),this.length=6,this.valueArr=Array.from({length:this.length}).map((()=>"")),this.numerics=[],this.shouldInputBeEnabled=t=>this.valueArr.slice(0,t).every((t=>""!==t)),this.handleKeyDown=(t,e)=>{const r=t.target,n=this.getInputElement(r);if(!n)return;["ArrowLeft","ArrowRight","Shift","Delete"].includes(t.key)&&t.preventDefault();const i=n.selectionStart;switch(t.key){case"ArrowLeft":i&&n.setSelectionRange(i+1,i+1),this.focusInputField("prev",e);break;case"ArrowRight":case"Shift":this.focusInputField("next",e);break;case"Delete":case"Backspace":""===n.value?this.focusInputField("prev",e):this.updateInput(n,e,"")}},this.focusInputField=(t,e)=>{if("next"===t){const t=e+1;if(!this.shouldInputBeEnabled(t))return;const r=this.numerics[t<this.length?t:e],n=r?this.getInputElement(r):void 0;n&&(n.disabled=!1,n.focus())}if("prev"===t){const t=e-1,r=this.numerics[t>-1?t:e],n=r?this.getInputElement(r):void 0;n&&n.focus()}}}firstUpdated(){const t=this.shadowRoot?.querySelectorAll("wui-input-numeric");t&&(this.numerics=Array.from(t)),this.numerics[0]?.focus()}render(){return G`
      <wui-flex gap="xxs" data-testid="wui-otp-input">
        ${Array.from({length:this.length}).map(((t,e)=>G`
            <wui-input-numeric
              @input=${t=>this.handleInput(t,e)}
              @keydown=${t=>this.handleKeyDown(t,e)}
              .disabled=${!this.shouldInputBeEnabled(e)}
            >
            </wui-input-numeric>
          `))}
      </wui-flex>
    `}updateInput(t,e,r){const n=this.numerics[e],i=t||(n?this.getInputElement(n):void 0);i&&(i.value=r,this.valueArr=this.valueArr.map(((t,n)=>n===e?r:t)))}handleInput(t,e){const r=t.target,n=this.getInputElement(r);if(n){const r=n.value;"insertFromPaste"===t.inputType?this.handlePaste(n,r,e):fr.isNumber(r)&&t.data?(this.updateInput(n,e,t.data),this.focusInputField("next",e)):this.updateInput(n,e,"")}this.dispatchInputChangeEvent()}handlePaste(t,e,r){const n=e[0];if(n&&fr.isNumber(n)){this.updateInput(t,r,n);const i=e.substring(1);if(r+1<this.length&&i.length){const t=this.numerics[r+1],e=t?this.getInputElement(t):void 0;e&&this.handlePaste(e,i,r+1)}else this.focusInputField("next",r)}else this.updateInput(t,r,"")}getInputElement(t){return t.shadowRoot?.querySelector("input")?t.shadowRoot.querySelector("input"):null}dispatchInputChangeEvent(){const t=this.valueArr.join("");this.dispatchEvent(new CustomEvent("inputChange",{detail:t,bubbles:!0,composed:!0}))}};ci.styles=[bt,oi],ai([Ct({type:Number})],ci.prototype,"length",void 0),ai([Mt()],ci.prototype,"valueArr",void 0),ci=ai([xt("wui-otp")],ci);var li=r(92592);function ui(t,e,r){return t!==e&&(t-e<0?e-t:t-e)<=r+.1}const hi={generate(t,e,r){const n="#141414",i=[],s=function(t,e){const r=Array.prototype.slice.call(li.create(t,{errorCorrectionLevel:"Q"}).modules.data,0),n=Math.sqrt(r.length);return r.reduce(((t,e,r)=>(r%n==0?t.push([e]):t[t.length-1].push(e))&&t),[])}(t),o=e/s.length,a=[{x:0,y:0},{x:1,y:0},{x:0,y:1}];a.forEach((({x:t,y:e})=>{const r=(s.length-7)*o*t,c=(s.length-7)*o*e,l=.45;for(let t=0;t<a.length;t+=1){const e=o*(7-2*t);i.push(K`
            <rect
              fill=${2===t?n:"transparent"}
              width=${0===t?e-5:e}
              rx= ${0===t?(e-5)*l:e*l}
              ry= ${0===t?(e-5)*l:e*l}
              stroke=${n}
              stroke-width=${0===t?5:0}
              height=${0===t?e-5:e}
              x= ${0===t?c+o*t+2.5:c+o*t}
              y= ${0===t?r+o*t+2.5:r+o*t}
            />
          `)}}));const c=Math.floor((r+25)/o),l=s.length/2-c/2,u=s.length/2+c/2-1,h=[];s.forEach(((t,e)=>{t.forEach(((t,r)=>{if(s[e][r]&&!(e<7&&r<7||e>s.length-8&&r<7||e<7&&r>s.length-8||e>l&&e<u&&r>l&&r<u)){const t=e*o+o/2,n=r*o+o/2;h.push([t,n])}}))}));const d={};return h.forEach((([t,e])=>{d[t]?d[t]?.push(e):d[t]=[e]})),Object.entries(d).map((([t,e])=>{const r=e.filter((t=>e.every((e=>!ui(t,e,o)))));return[Number(t),r]})).forEach((([t,e])=>{e.forEach((e=>{i.push(K`<circle cx=${t} cy=${e} fill=${n} r=${o/2.5} />`)}))})),Object.entries(d).filter((([t,e])=>e.length>1)).map((([t,e])=>{const r=e.filter((t=>e.some((e=>ui(t,e,o)))));return[Number(t),r]})).map((([t,e])=>{e.sort(((t,e)=>t<e?-1:1));const r=[];for(const t of e){const e=r.find((e=>e.some((e=>ui(t,e,o)))));e?e.push(t):r.push([t])}return[t,r.map((t=>[t[0],t[t.length-1]]))]})).forEach((([t,e])=>{e.forEach((([e,r])=>{i.push(K`
              <line
                x1=${t}
                x2=${t}
                y1=${e}
                y2=${r}
                stroke=${n}
                stroke-width=${o/1.25}
                stroke-linecap="round"
              />
            `)}))})),i}},di=l`
  :host {
    position: relative;
    user-select: none;
    display: block;
    overflow: hidden;
    aspect-ratio: 1 / 1;
    width: var(--local-size);
  }

  :host([data-theme='dark']) {
    border-radius: clamp(0px, var(--wui-border-radius-l), 40px);
    background-color: var(--wui-color-inverse-100);
    padding: var(--wui-spacing-l);
  }

  :host([data-theme='light']) {
    box-shadow: 0 0 0 1px var(--wui-color-bg-125);
    background-color: var(--wui-color-bg-125);
  }

  svg:first-child,
  wui-image,
  wui-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translateY(-50%) translateX(-50%);
  }

  wui-image {
    width: 25%;
    height: 25%;
    border-radius: var(--wui-border-radius-xs);
  }

  wui-icon {
    width: 100%;
    height: 100%;
    color: #3396ff !important;
    transform: translateY(-50%) translateX(-50%) scale(0.25);
  }
`;var pi=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let fi=class extends ht{constructor(){super(...arguments),this.uri="",this.size=0,this.theme="dark",this.imageSrc=void 0,this.alt=void 0}render(){return this.dataset.theme=this.theme,this.style.cssText=`--local-size: ${this.size}px`,G`${this.templateVisual()} ${this.templateSvg()}`}templateSvg(){const t="light"===this.theme?this.size:this.size-32;return K`
      <svg height=${t} width=${t}>
        ${hi.generate(this.uri,t,t/4)}
      </svg>
    `}templateVisual(){return this.imageSrc?G`<wui-image src=${this.imageSrc} alt=${this.alt??"logo"}></wui-image>`:G`<wui-icon size="inherit" color="inherit" name="walletConnect"></wui-icon>`}};fi.styles=[bt,di],pi([Ct()],fi.prototype,"uri",void 0),pi([Ct({type:Number})],fi.prototype,"size",void 0),pi([Ct()],fi.prototype,"theme",void 0),pi([Ct()],fi.prototype,"imageSrc",void 0),pi([Ct()],fi.prototype,"alt",void 0),fi=pi([xt("wui-qr-code")],fi);const mi=l`
  :host {
    position: relative;
    display: inline-block;
    width: 100%;
  }
`;let gi=class extends ht{constructor(){super(...arguments),this.inputComponentRef=hn()}render(){return G`
      <wui-input-text
        ${fn(this.inputComponentRef)}
        placeholder="Search wallet"
        icon="search"
        type="search"
        enterKeyHint="search"
        size="sm"
      >
        <wui-input-element @click=${this.clearValue} icon="close"></wui-input-element>
      </wui-input-text>
    `}clearValue(){const t=this.inputComponentRef.value?.inputElementRef.value;t&&(t.value="",t.focus(),t.dispatchEvent(new Event("input")))}};gi.styles=[bt,mi],gi=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o}([xt("wui-search-bar")],gi);const yi=l`
  :host {
    display: flex;
    column-gap: var(--wui-spacing-xs);
    align-items: center;
    padding: 7px var(--wui-spacing-l) 7px var(--wui-spacing-xs);
    border-radius: var(--wui-border-radius-3xl);
    border: 1px solid var(--wui-gray-glass-005);
    background-color: var(--wui-color-bg-175);
    box-shadow:
      0px 14px 64px -4px rgba(0, 0, 0, 0.15),
      0px 8px 22px -6px rgba(0, 0, 0, 0.15);
  }
`;var wi=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let bi=class extends ht{constructor(){super(...arguments),this.backgroundColor="accent-100",this.iconColor="accent-100",this.icon="checkmark",this.message=""}render(){return G`
      <wui-icon-box
        size="xs"
        iconColor=${this.iconColor}
        backgroundColor=${this.backgroundColor}
        icon=${this.icon}
      ></wui-icon-box>
      <wui-text variant="paragraph-500" color="fg-100">${this.message}</wui-text>
    `}};bi.styles=[bt,yi],wi([Ct()],bi.prototype,"backgroundColor",void 0),wi([Ct()],bi.prototype,"iconColor",void 0),wi([Ct()],bi.prototype,"icon",void 0),wi([Ct()],bi.prototype,"message",void 0),bi=wi([xt("wui-snackbar")],bi);const vi=l`
  :host {
    display: inline-flex;
    background-color: var(--wui-gray-glass-002);
    border-radius: var(--wui-border-radius-3xl);
    padding: var(--wui-spacing-3xs);
    position: relative;
    height: 36px;
    overflow: hidden;
  }

  :host::before {
    content: '';
    position: absolute;
    pointer-events: none;
    top: 4px;
    left: 4px;
    display: block;
    width: var(--local-tab-width);
    height: 28px;
    border-radius: var(--wui-border-radius-3xl);
    background-color: var(--wui-gray-glass-002);
    box-shadow: inset 0 0 0 1px var(--wui-gray-glass-002);
    transform: translateX(calc(var(--local-tab) * var(--local-tab-width)));
    transition: transform var(--wui-ease-out-power-2) var(--wui-duration-lg);
  }

  :host([data-type='flex'])::before {
    left: 3px;
    transform: translateX(calc((var(--local-tab) * 34px) + (var(--local-tab) * 4px)));
  }

  :host([data-type='flex']) {
    display: flex;
    padding: 0px 0px 0px 12px;
    gap: 4px;
  }

  :host([data-type='flex']) > button > wui-text {
    position: absolute;
    left: 18px;
    opacity: 0;
  }

  button[data-active='true'] > wui-icon,
  button[data-active='true'] > wui-text {
    color: var(--wui-color-fg-100);
  }

  button[data-active='false'] > wui-icon,
  button[data-active='false'] > wui-text {
    color: var(--wui-color-fg-200);
  }

  button[data-active='true']:disabled,
  button[data-active='false']:disabled {
    background-color: transparent;
    opacity: 0.5;
    cursor: not-allowed;
  }

  button[data-active='true']:disabled > wui-text {
    color: var(--wui-color-fg-200);
  }

  button[data-active='false']:disabled > wui-text {
    color: var(--wui-color-fg-300);
  }

  button > wui-icon,
  button > wui-text {
    pointer-events: none;
    transition: all var(--wui-ease-out-power-2) var(--wui-duration-lg);
  }

  button {
    width: var(--local-tab-width);
  }

  :host([data-type='flex']) > button {
    width: 34px;
    position: relative;
    display: flex;
    justify-content: flex-start;
  }

  button:hover:enabled,
  button:active:enabled {
    background-color: transparent !important;
  }

  button:hover:enabled > wui-icon,
  button:active:enabled > wui-icon {
    color: var(--wui-color-fg-125);
  }

  button:hover:enabled > wui-text,
  button:active:enabled > wui-text {
    color: var(--wui-color-fg-125);
  }

  button {
    border-radius: var(--wui-border-radius-3xl);
  }
`;var Ei=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let xi=class extends ht{constructor(){super(...arguments),this.tabs=[],this.onTabChange=()=>null,this.buttons=[],this.disabled=!1,this.activeTab=0,this.localTabWidth="100px",this.isDense=!1}render(){return this.isDense=this.tabs.length>3,this.style.cssText=`\n      --local-tab: ${this.activeTab};\n      --local-tab-width: ${this.localTabWidth};\n    `,this.dataset.type=this.isDense?"flex":"block",this.tabs.map(((t,e)=>{const r=e===this.activeTab;return G`
        <button
          ?disabled=${this.disabled}
          @click=${()=>this.onTabClick(e)}
          data-active=${r}
        >
          <wui-icon size="xs" color="inherit" name=${t.icon}></wui-icon>
          <wui-text variant="small-600" color="inherit"> ${t.label} </wui-text>
        </button>
      `}))}firstUpdated(){this.shadowRoot&&this.isDense&&(this.buttons=[...this.shadowRoot.querySelectorAll("button")],setTimeout((()=>{this.animateTabs(0,!0)}),0))}onTabClick(t){this.buttons&&this.animateTabs(t,!1),this.activeTab=t,this.onTabChange(t)}animateTabs(t,e){const r=this.buttons[this.activeTab],n=this.buttons[t],i=r?.querySelector("wui-text"),s=n?.querySelector("wui-text"),o=n?.getBoundingClientRect(),a=s?.getBoundingClientRect();r&&i&&!e&&t!==this.activeTab&&(i.animate([{opacity:0}],{duration:50,easing:"ease",fill:"forwards"}),r.animate([{width:"34px"}],{duration:500,easing:"ease",fill:"forwards"})),n&&o&&a&&s&&(t!==this.activeTab||e)&&(this.localTabWidth=`${Math.round(o.width+a.width)+6}px`,n.animate([{width:`${o.width+a.width}px`}],{duration:e?0:500,fill:"forwards",easing:"ease"}),s.animate([{opacity:1}],{duration:e?0:125,delay:e?0:200,fill:"forwards",easing:"ease"}))}};xi.styles=[bt,vt,vi],Ei([Ct({type:Array})],xi.prototype,"tabs",void 0),Ei([Ct()],xi.prototype,"onTabChange",void 0),Ei([Ct({type:Array})],xi.prototype,"buttons",void 0),Ei([Ct({type:Boolean})],xi.prototype,"disabled",void 0),Ei([Mt()],xi.prototype,"activeTab",void 0),Ei([Mt()],xi.prototype,"localTabWidth",void 0),Ei([Mt()],xi.prototype,"isDense",void 0),xi=Ei([xt("wui-tabs")],xi);const _i=l`
  :host {
    display: block;
    padding: 9px var(--wui-spacing-s) 10px var(--wui-spacing-s);
    border-radius: var(--wui-border-radius-xxs);
    background-color: var(--wui-color-fg-100);
    color: var(--wui-color-bg-100);
    position: relative;
  }

  wui-icon {
    position: absolute;
    width: 12px !important;
    height: 4px !important;
  }

  wui-icon[data-placement='top'] {
    bottom: 0;
    left: 50%;
    transform: translate(-50%, 95%);
  }

  wui-icon[data-placement='bottom'] {
    top: 0;
    left: 50%;
    transform: translate(-50%, -95%) rotate(180deg);
  }

  wui-icon[data-placement='right'] {
    top: 50%;
    left: 0;
    transform: translate(-65%, -50%) rotate(90deg);
  }

  wui-icon[data-placement='left'] {
    top: 50%;
    right: 0%;
    transform: translate(65%, -50%) rotate(270deg);
  }
`;var Ai=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let ki=class extends ht{constructor(){super(...arguments),this.placement="top",this.message=""}render(){return G`<wui-icon
        data-placement=${this.placement}
        color="fg-100"
        size="inherit"
        name="cursor"
      ></wui-icon>
      <wui-text color="inherit" variant="small-500">${this.message}</wui-text>`}};ki.styles=[bt,vt,_i],Ai([Ct()],ki.prototype,"placement",void 0),Ai([Ct()],ki.prototype,"message",void 0),ki=Ai([xt("wui-tooltip")],ki);const Si=l`
  :host {
    display: flex;
    justify-content: center;
    align-items: center;
    width: var(--wui-icon-box-size-xl);
    height: var(--wui-icon-box-size-xl);
    box-shadow: 0 0 0 8px var(--wui-thumbnail-border);
    border-radius: var(--local-border-radius);
    overflow: hidden;
  }

  wui-icon {
    width: 32px;
    height: 32px;
  }
`;var Ci=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Mi=class extends ht{render(){return this.style.cssText=`--local-border-radius: ${this.borderRadiusFull?"1000px":"20px"};`,G`${this.templateVisual()}`}templateVisual(){return this.imageSrc?G`<wui-image src=${this.imageSrc} alt=${this.alt??""}></wui-image>`:G`<wui-icon
      data-parent-size="md"
      size="inherit"
      color="inherit"
      name="walletPlaceholder"
    ></wui-icon>`}};Mi.styles=[bt,Si],Ci([Ct()],Mi.prototype,"imageSrc",void 0),Ci([Ct()],Mi.prototype,"alt",void 0),Ci([Ct({type:Boolean})],Mi.prototype,"borderRadiusFull",void 0),Mi=Ci([xt("wui-visual-thumbnail")],Mi);const Ii=l`
  :host {
    display: block;
  }

  button {
    width: 100%;
    display: block;
    padding-top: var(--wui-spacing-l);
    padding-bottom: var(--wui-spacing-l);
    padding-left: var(--wui-spacing-s);
    padding-right: var(--wui-spacing-2l);
    border-radius: var(--wui-border-radius-s);
    background-color: var(--wui-accent-glass-015);
  }

  button:hover {
    background-color: var(--wui-accent-glass-010) !important;
  }

  button:active {
    background-color: var(--wui-accent-glass-020) !important;
  }
`;var Pi=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Oi=class extends ht{constructor(){super(...arguments),this.label="",this.description="",this.icon="wallet"}render(){return G`
      <button>
        <wui-flex gap="m" alignItems="center" justifyContent="space-between">
          <wui-icon-box
            size="lg"
            iconcolor="accent-100"
            backgroundcolor="accent-100"
            icon=${this.icon}
            background="transparent"
          ></wui-icon-box>

          <wui-flex flexDirection="column" gap="3xs">
            <wui-text variant="paragraph-500" color="fg-100">${this.label}</wui-text>
            <wui-text variant="small-400" color="fg-200">${this.description}</wui-text>
          </wui-flex>

          <wui-icon size="md" color="fg-200" name="chevronRight"></wui-icon>
        </wui-flex>
      </button>
    `}};Oi.styles=[bt,vt,Ii],Pi([Ct()],Oi.prototype,"label",void 0),Pi([Ct()],Oi.prototype,"description",void 0),Pi([Ct()],Oi.prototype,"icon",void 0),Oi=Pi([xt("wui-notice-card")],Oi);const Ti=l`
  button {
    height: auto;
    position: relative;
    flex-direction: column;
    gap: var(--wui-spacing-s);
    padding: 17px 18px 17px var(--wui-spacing-m);
    width: 100%;
    background-color: var(--wui-gray-glass-002);
    border-radius: var(--wui-border-radius-xs);
    color: var(--wui-color-fg-250);
  }

  .overflowedContent {
    width: 100%;
    overflow: hidden;
  }

  .overflowedContent[data-active='false']:after {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to top, var(--wui-color-bg-200), transparent);
    border-bottom-left-radius: var(--wui-border-radius-xs);
    border-bottom-right-radius: var(--wui-border-radius-xs);
  }

  .heightContent {
    max-height: 100px;
  }

  pre {
    text-align: left;
    white-space: pre-wrap;
    height: auto;
    overflow-x: auto;
    overflow-wrap: anywhere;
  }
`;var Ni=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Ri=class extends ht{constructor(){super(...arguments),this.textTitle="",this.overflowedContent="",this.toggled=!1,this.enableAccordion=!1,this.scrollElement=void 0,this.scrollHeightElement=0}firstUpdated(){setTimeout((()=>{const t=this.shadowRoot?.querySelector(".heightContent");if(t){this.scrollElement=t;const e=t?.scrollHeight;e&&e>100&&(this.enableAccordion=!0,this.scrollHeightElement=e,this.requestUpdate())}}),0)}render(){return G`
      <button ontouchstart @click=${()=>this.onClick()}>
        <wui-flex justifyContent="space-between" alignItems="center">
          <wui-text variant="paragraph-500" color="fg-100">${this.textTitle}</wui-text>
          ${this.chevronTemplate()}
        </wui-flex>
        <div
          data-active=${!this.enableAccordion||Boolean(this.toggled)}
          class="overflowedContent"
        >
          <div class="heightContent">
            <wui-text variant="paragraph-400" color="fg-200">
              <pre>${this.overflowedContent}</pre>
            </wui-text>
          </div>
        </div>
      </button>
    `}onClick(){const t=this.shadowRoot?.querySelector("wui-icon");this.enableAccordion&&(this.toggled=!this.toggled,this.requestUpdate(),this.scrollElement&&this.scrollElement.animate([{maxHeight:this.toggled?"100px":`${this.scrollHeightElement}px`},{maxHeight:this.toggled?`${this.scrollHeightElement}px`:"100px"}],{duration:300,fill:"forwards",easing:"ease"}),t&&t.animate([{transform:this.toggled?"rotate(0deg)":"rotate(180deg)"},{transform:this.toggled?"rotate(180deg)":"rotate(0deg)"}],{duration:300,fill:"forwards",easing:"ease"}))}chevronTemplate(){return this.enableAccordion?G` <wui-icon color="fg-100" size="sm" name="chevronBottom"></wui-icon>`:null}};Ri.styles=[bt,vt,Ti],Ni([Ct()],Ri.prototype,"textTitle",void 0),Ni([Ct()],Ri.prototype,"overflowedContent",void 0),Ri=Ni([xt("wui-list-accordion")],Ri);const Bi=l`
  :host {
    display: flex;
    column-gap: var(--wui-spacing-s);
    padding: 17px 18px 17px var(--wui-spacing-m);
    width: 100%;
    background-color: var(--wui-gray-glass-002);
    border-radius: var(--wui-border-radius-xs);
    color: var(--wui-color-fg-250);
  }

  wui-image {
    width: var(--wui-icon-size-lg);
    height: var(--wui-icon-size-lg);
    border-radius: var(--wui-border-radius-3xl);
  }

  wui-icon {
    width: var(--wui-icon-size-lg);
    height: var(--wui-icon-size-lg);
  }
`;var ji=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Li=class extends ht{constructor(){super(...arguments),this.imageSrc=void 0,this.textTitle="",this.textValue=void 0}render(){return G`
      <wui-flex justifyContent="space-between" alignItems="center">
        <wui-text variant="paragraph-500" color=${this.textValue?"fg-200":"fg-100"}>
          ${this.textTitle}
        </wui-text>
        ${this.templateContent()}
      </wui-flex>
    `}templateContent(){return this.imageSrc?G`<wui-image src=${this.imageSrc} alt=${this.textTitle}></wui-image>`:this.textValue?G` <wui-text variant="paragraph-400" color="fg-100"> ${this.textValue} </wui-text>`:G`<wui-icon size="inherit" color="fg-200" name="networkPlaceholder"></wui-icon>`}};Li.styles=[bt,vt,Bi],ji([Ct()],Li.prototype,"imageSrc",void 0),ji([Ct()],Li.prototype,"textTitle",void 0),ji([Ct()],Li.prototype,"textValue",void 0),Li=ji([xt("wui-list-content")],Li);const Ui=l`
  :host {
    display: flex;
    flex-direction: column;
    gap: var(--wui-spacing-l);
    padding: 17px 18px 17px var(--wui-spacing-m);
    width: 100%;
    background-color: var(--wui-gray-glass-002);
    border-radius: var(--wui-border-radius-xs);
    color: var(--wui-color-fg-250);
  }

  wui-image {
    width: var(--wui-icon-size-lg);
    height: var(--wui-icon-size-lg);
    border-radius: var(--wui-border-radius-3xl);
  }

  wui-icon {
    width: var(--wui-icon-size-lg);
    height: var(--wui-icon-size-lg);
  }
`;var Di=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let $i=class extends ht{constructor(){super(...arguments),this.amount="",this.networkCurreny="",this.networkImageUrl="",this.receiverAddress=""}render(){return G`
      <wui-flex justifyContent="space-between" alignItems="center">
        <wui-text variant="paragraph-500" color="fg-200">Sending</wui-text>
        <wui-flex gap="xs" alignItems="center">
          <wui-text variant="paragraph-400" color="fg-100">
            ${this.amount} ${this.networkCurreny}
          </wui-text>
          ${this.templateNetworkVisual()}
        </wui-flex>
      </wui-flex>
      <wui-flex justifyContent="space-between" alignItems="center">
        <wui-text variant="paragraph-500" color="fg-200">To</wui-text>
        <wui-chip
          icon="externalLink"
          variant="shadeSmall"
          href=${this.receiverAddress}
          title=${this.receiverAddress}
        ></wui-chip>
      </wui-flex>
    `}templateNetworkVisual(){return this.networkImageUrl?G`<wui-image src=${this.networkImageUrl} alt="Network Image"></wui-image>`:G`<wui-icon size="inherit" color="fg-200" name="networkPlaceholder"></wui-icon>`}};$i.styles=[bt,vt,Ui],Di([Ct()],$i.prototype,"amount",void 0),Di([Ct()],$i.prototype,"networkCurreny",void 0),Di([Ct()],$i.prototype,"networkImageUrl",void 0),Di([Ct()],$i.prototype,"receiverAddress",void 0),$i=Di([xt("wui-list-wallet-transaction")],$i);const Fi=l`
  :host {
    display: grid;
    width: inherit;
    height: inherit;
  }
`;var zi=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Hi=class extends ht{render(){return this.style.cssText=`\n      grid-template-rows: ${this.gridTemplateRows};\n      grid-template-columns: ${this.gridTemplateColumns};\n      justify-items: ${this.justifyItems};\n      align-items: ${this.alignItems};\n      justify-content: ${this.justifyContent};\n      align-content: ${this.alignContent};\n      column-gap: ${this.columnGap&&`var(--wui-spacing-${this.columnGap})`};\n      row-gap: ${this.rowGap&&`var(--wui-spacing-${this.rowGap})`};\n      gap: ${this.gap&&`var(--wui-spacing-${this.gap})`};\n      padding-top: ${this.padding&&fr.getSpacingStyles(this.padding,0)};\n      padding-right: ${this.padding&&fr.getSpacingStyles(this.padding,1)};\n      padding-bottom: ${this.padding&&fr.getSpacingStyles(this.padding,2)};\n      padding-left: ${this.padding&&fr.getSpacingStyles(this.padding,3)};\n      margin-top: ${this.margin&&fr.getSpacingStyles(this.margin,0)};\n      margin-right: ${this.margin&&fr.getSpacingStyles(this.margin,1)};\n      margin-bottom: ${this.margin&&fr.getSpacingStyles(this.margin,2)};\n      margin-left: ${this.margin&&fr.getSpacingStyles(this.margin,3)};\n    `,G`<slot></slot>`}};Hi.styles=[bt,Fi],zi([Ct()],Hi.prototype,"gridTemplateRows",void 0),zi([Ct()],Hi.prototype,"gridTemplateColumns",void 0),zi([Ct()],Hi.prototype,"justifyItems",void 0),zi([Ct()],Hi.prototype,"alignItems",void 0),zi([Ct()],Hi.prototype,"justifyContent",void 0),zi([Ct()],Hi.prototype,"alignContent",void 0),zi([Ct()],Hi.prototype,"columnGap",void 0),zi([Ct()],Hi.prototype,"rowGap",void 0),zi([Ct()],Hi.prototype,"gap",void 0),zi([Ct()],Hi.prototype,"padding",void 0),zi([Ct()],Hi.prototype,"margin",void 0),Hi=zi([xt("wui-grid")],Hi);const Wi=l`
  :host {
    position: relative;
    display: flex;
    width: 100%;
    height: 1px;
    background-color: var(--wui-gray-glass-005);
    justify-content: center;
    align-items: center;
  }

  :host > wui-text {
    position: absolute;
    padding: 0px 10px;
    background-color: var(--wui-color-modal-bg);
  }
`;var qi=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Vi=class extends ht{constructor(){super(...arguments),this.text=""}render(){return G`${this.template()}`}template(){return this.text?G`<wui-text variant="small-500" color="fg-200">${this.text}</wui-text>`:null}};Vi.styles=[bt,Wi],qi([Ct()],Vi.prototype,"text",void 0),Vi=qi([xt("wui-separator")],Vi);var Gi=r(10248);const Ki=["receive","deposit","borrow","claim"],Zi=["withdraw","repay","burn"],Ji={getTransactionGroupTitle:t=>t===Gi.E.getYear()?"This Year":t,getTransactionImages(t){const[e,r]=t,n=Boolean(e)&&t?.every((t=>Boolean(t.nft_info))),i=t?.length>1;return 2!==t?.length||n?i?t.map((t=>this.getTransactionImage(t))):[this.getTransactionImage(e)]:[this.getTransactionImage(e),this.getTransactionImage(r)]},getTransactionImage:t=>({type:Ji.getTransactionTransferTokenType(t),url:Ji.getTransactionImageURL(t)}),getTransactionImageURL(t){let e=null;const r=Boolean(t?.nft_info),n=Boolean(t?.fungible_info);return t&&r?e=t?.nft_info?.content?.preview?.url:t&&n&&(e=t?.fungible_info?.icon?.url),e},getTransactionTransferTokenType:t=>t?.fungible_info?"FUNGIBLE":t?.nft_info?"NFT":null,getTransactionDescriptions(t){const e=t.metadata?.operationType,r=t.transfers,n=t.transfers?.length>0,i=t.transfers?.length>1,s=n&&r?.every((t=>Boolean(t.fungible_info))),[o,a]=r;let c=this.getTransferDescription(o),l=this.getTransferDescription(a);if(!n)return"send"!==e&&"receive"!==e||!s?[t.metadata.status]:(c=fr.getTruncateString({string:t.metadata.sentFrom,charsStart:4,charsEnd:6,truncate:"middle"}),l=fr.getTruncateString({string:t.metadata.sentTo,charsStart:4,charsEnd:6,truncate:"middle"}),[c,l]);if(i)return r.map((t=>this.getTransferDescription(t)));let u="";return Ki.includes(e)?u="+":Zi.includes(e)&&(u="-"),c=u.concat(c),[c]},getTransferDescription(t){let e="";return t?(t?.nft_info?e=t?.nft_info?.name||"-":t?.fungible_info&&(e=this.getFungibleTransferDescription(t)||"-"),e):e},getFungibleTransferDescription(t){return t?[this.getQuantityFixedValue(t?.quantity.numeric),t?.fungible_info?.symbol].join(" ").trim():null},getQuantityFixedValue:t=>t?parseFloat(t).toFixed(3):null}},80252:(t,e,r)=>{"use strict";r.r(e),r.d(e,{EIP6963Connector:()=>hi,EmailConnector:()=>gi,createWeb3Modal:()=>Yi,defaultWagmiConfig:()=>Qi,walletConnectProvider:()=>Ji});var n=r(46070),i=r(66403),s=r(95909),o=r(31123),a=r(74666),c=r(2927),l=r(88382);const u=t=>t??l.Ld;var h=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let d=class extends a.oi{constructor(){super(),this.unsubscribe=[],this.disabled=!1,this.balance="show",this.charsStart=4,this.charsEnd=6,this.address=s.AccountController.state.address,this.balanceVal=s.AccountController.state.balance,this.balanceSymbol=s.AccountController.state.balanceSymbol,this.profileName=s.AccountController.state.profileName,this.profileImage=s.AccountController.state.profileImage,this.network=s.NetworkController.state.caipNetwork,this.unsubscribe.push(s.AccountController.subscribe((t=>{t.isConnected?(this.address=t.address,this.balanceVal=t.balance,this.profileName=t.profileName,this.profileImage=t.profileImage,this.balanceSymbol=t.balanceSymbol):(this.address="",this.balanceVal="",this.profileName="",this.profileImage="",this.balanceSymbol="")})),s.NetworkController.subscribeKey("caipNetwork",(t=>this.network=t)))}disconnectedCallback(){this.unsubscribe.forEach((t=>t()))}render(){const t=s.fz.getNetworkImage(this.network),e="show"===this.balance;return a.dy`
      <wui-account-button
        .disabled=${Boolean(this.disabled)}
        address=${u(this.profileName??this.address)}
        ?isProfileName=${Boolean(this.profileName)}
        networkSrc=${u(t)}
        avatarSrc=${u(this.profileImage)}
        balance=${e?s.j1.formatBalance(this.balanceVal,this.balanceSymbol):""}
        @click=${this.onClick.bind(this)}
        data-testid="account-button"
        .charsStart=${this.charsStart}
        .charsEnd=${this.charsEnd}
      >
      </wui-account-button>
    `}onClick(){s.IN.open()}};h([(0,c.Cb)({type:Boolean})],d.prototype,"disabled",void 0),h([(0,c.Cb)()],d.prototype,"balance",void 0),h([(0,c.Cb)()],d.prototype,"charsStart",void 0),h([(0,c.Cb)()],d.prototype,"charsEnd",void 0),h([(0,c.SB)()],d.prototype,"address",void 0),h([(0,c.SB)()],d.prototype,"balanceVal",void 0),h([(0,c.SB)()],d.prototype,"balanceSymbol",void 0),h([(0,c.SB)()],d.prototype,"profileName",void 0),h([(0,c.SB)()],d.prototype,"profileImage",void 0),h([(0,c.SB)()],d.prototype,"network",void 0),d=h([(0,o.customElement)("w3m-account-button")],d);var p=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let f=class extends a.oi{constructor(){super(),this.unsubscribe=[],this.disabled=!1,this.balance=void 0,this.size=void 0,this.label=void 0,this.loadingLabel=void 0,this.charsStart=4,this.charsEnd=6,this.isAccount=s.AccountController.state.isConnected,this.unsubscribe.push(s.AccountController.subscribeKey("isConnected",(t=>{this.isAccount=t})))}disconnectedCallback(){this.unsubscribe.forEach((t=>t()))}render(){return this.isAccount?a.dy`
          <w3m-account-button
            .disabled=${Boolean(this.disabled)}
            balance=${u(this.balance)}
            .charsStart=${u(this.charsStart)}
            .charsEnd=${u(this.charsEnd)}
          >
          </w3m-account-button>
        `:a.dy`
          <w3m-connect-button
            size=${u(this.size)}
            label=${u(this.label)}
            loadingLabel=${u(this.loadingLabel)}
          ></w3m-connect-button>
        `}};p([(0,c.Cb)({type:Boolean})],f.prototype,"disabled",void 0),p([(0,c.Cb)()],f.prototype,"balance",void 0),p([(0,c.Cb)()],f.prototype,"size",void 0),p([(0,c.Cb)()],f.prototype,"label",void 0),p([(0,c.Cb)()],f.prototype,"loadingLabel",void 0),p([(0,c.Cb)()],f.prototype,"charsStart",void 0),p([(0,c.Cb)()],f.prototype,"charsEnd",void 0),p([(0,c.SB)()],f.prototype,"isAccount",void 0),f=p([(0,o.customElement)("w3m-button")],f);var m=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let g=class extends a.oi{constructor(){super(),this.unsubscribe=[],this.size="md",this.label="Connect Wallet",this.loadingLabel="Connecting...",this.open=s.IN.state.open,this.loading=s.IN.state.loading,this.unsubscribe.push(s.IN.subscribe((t=>{this.open=t.open,this.loading=t.loading})))}disconnectedCallback(){this.unsubscribe.forEach((t=>t()))}render(){const t=this.loading||this.open;return a.dy`
      <wui-connect-button
        size=${u(this.size)}
        .loading=${t}
        @click=${this.onClick.bind(this)}
        data-testid="connect-button"
      >
        ${t?this.loadingLabel:this.label}
      </wui-connect-button>
    `}onClick(){this.open?s.IN.close():s.IN.open()}};m([(0,c.Cb)()],g.prototype,"size",void 0),m([(0,c.Cb)()],g.prototype,"label",void 0),m([(0,c.Cb)()],g.prototype,"loadingLabel",void 0),m([(0,c.SB)()],g.prototype,"open",void 0),m([(0,c.SB)()],g.prototype,"loading",void 0),g=m([(0,o.customElement)("w3m-connect-button")],g),r(96541);var y=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let w=class extends a.oi{constructor(){super(),this.unsubscribe=[],this.disabled=!1,this.network=s.NetworkController.state.caipNetwork,this.connected=s.AccountController.state.isConnected,this.loading=s.IN.state.loading,this.unsubscribe.push(s.NetworkController.subscribeKey("caipNetwork",(t=>this.network=t)),s.AccountController.subscribeKey("isConnected",(t=>this.connected=t)),s.IN.subscribeKey("loading",(t=>this.loading=t)))}disconnectedCallback(){this.unsubscribe.forEach((t=>t()))}render(){return a.dy`
      <wui-network-button
        .disabled=${Boolean(this.disabled||this.loading)}
        imageSrc=${u(s.fz.getNetworkImage(this.network))}
        @click=${this.onClick.bind(this)}
      >
        ${this.network?.name??(this.connected?"Unknown Network":"Select Network")}
      </wui-network-button>
    `}onClick(){s.IN.open({view:"Networks"})}};y([(0,c.Cb)({type:Boolean})],w.prototype,"disabled",void 0),y([(0,c.SB)()],w.prototype,"network",void 0),y([(0,c.SB)()],w.prototype,"connected",void 0),y([(0,c.SB)()],w.prototype,"loading",void 0),w=y([(0,o.customElement)("w3m-network-button")],w);const b=a.iv`
  :host {
    display: block;
    will-change: transform, opacity;
  }
`;var v=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let E=class extends a.oi{constructor(){super(),this.resizeObserver=void 0,this.prevHeight="0px",this.prevHistoryLength=1,this.unsubscribe=[],this.view=s.RouterController.state.view,this.unsubscribe.push(s.RouterController.subscribeKey("view",(t=>this.onViewChange(t))))}firstUpdated(){this.resizeObserver=new ResizeObserver((async([t])=>{const e=`${t?.contentRect.height}px`;"0px"!==this.prevHeight&&(await this.animate([{height:this.prevHeight},{height:e}],{duration:150,easing:"ease",fill:"forwards"}).finished,this.style.height="auto"),this.prevHeight=e})),this.resizeObserver.observe(this.getWrapper())}disconnectedCallback(){this.resizeObserver?.unobserve(this.getWrapper()),this.unsubscribe.forEach((t=>t()))}render(){return a.dy`<div>${this.viewTemplate()}</div>`}viewTemplate(){switch(this.view){case"Connect":default:return a.dy`<w3m-connect-view></w3m-connect-view>`;case"ConnectingWalletConnect":return a.dy`<w3m-connecting-wc-view></w3m-connecting-wc-view>`;case"ConnectingExternal":return a.dy`<w3m-connecting-external-view></w3m-connecting-external-view>`;case"ConnectingSiwe":return a.dy`<w3m-connecting-siwe-view></w3m-connecting-siwe-view>`;case"AllWallets":return a.dy`<w3m-all-wallets-view></w3m-all-wallets-view>`;case"Networks":return a.dy`<w3m-networks-view></w3m-networks-view>`;case"SwitchNetwork":return a.dy`<w3m-network-switch-view></w3m-network-switch-view>`;case"Account":return a.dy`<w3m-account-view></w3m-account-view>`;case"WhatIsAWallet":return a.dy`<w3m-what-is-a-wallet-view></w3m-what-is-a-wallet-view>`;case"WhatIsANetwork":return a.dy`<w3m-what-is-a-network-view></w3m-what-is-a-network-view>`;case"GetWallet":return a.dy`<w3m-get-wallet-view></w3m-get-wallet-view>`;case"Downloads":return a.dy`<w3m-downloads-view></w3m-downloads-view>`;case"EmailVerifyOtp":return a.dy`<w3m-email-verify-otp-view></w3m-email-verify-otp-view>`;case"EmailVerifyDevice":return a.dy`<w3m-email-verify-device-view></w3m-email-verify-device-view>`;case"ApproveTransaction":return a.dy`<w3m-approve-transaction-view></w3m-approve-transaction-view>`;case"Transactions":return a.dy`<w3m-transactions-view></w3m-transactions-view>`;case"UpgradeEmailWallet":return a.dy`<w3m-upgrade-wallet-view></w3m-upgrade-wallet-view>`;case"UpdateEmailWallet":return a.dy`<w3m-update-email-wallet-view></w3m-update-email-wallet-view>`;case"UpdateEmailWalletWaiting":return a.dy`<w3m-update-email-wallet-waiting-view></w3m-update-email-wallet-waiting-view>`}}async onViewChange(t){const{history:e}=s.RouterController.state;let r=-10,n=10;e.length<this.prevHistoryLength&&(r=10,n=-10),this.prevHistoryLength=e.length,await this.animate([{opacity:1,transform:"translateX(0px)"},{opacity:0,transform:`translateX(${r}px)`}],{duration:150,easing:"ease",fill:"forwards"}).finished,this.view=t,await this.animate([{opacity:0,transform:`translateX(${n}px)`},{opacity:1,transform:"translateX(0px)"}],{duration:150,easing:"ease",fill:"forwards",delay:50}).finished}getWrapper(){return this.shadowRoot?.querySelector("div")}};E.styles=b,v([(0,c.SB)()],E.prototype,"view",void 0),E=v([(0,o.customElement)("w3m-router")],E);const x=a.iv`
  wui-flex {
    width: 100%;
  }

  :host > wui-flex:first-child {
    transform: translateY(calc(var(--wui-spacing-xxs) * -1));
  }

  wui-icon-link {
    margin-right: calc(var(--wui-icon-box-size-md) * -1);
  }

  wui-notice-card {
    margin-bottom: var(--wui-spacing-3xs);
  }
`;var _=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let A=class extends a.oi{constructor(){super(),this.usubscribe=[],this.address=s.AccountController.state.address,this.profileImage=s.AccountController.state.profileImage,this.profileName=s.AccountController.state.profileName,this.balance=s.AccountController.state.balance,this.balanceSymbol=s.AccountController.state.balanceSymbol,this.network=s.NetworkController.state.caipNetwork,this.disconecting=!1,this.usubscribe.push(s.AccountController.subscribe((t=>{t.address?(this.address=t.address,this.profileImage=t.profileImage,this.profileName=t.profileName,this.balance=t.balance,this.balanceSymbol=t.balanceSymbol):s.IN.close()})),s.NetworkController.subscribeKey("caipNetwork",(t=>{t?.id&&(this.network=t)})))}disconnectedCallback(){this.usubscribe.forEach((t=>t()))}render(){if(!this.address)throw new Error("w3m-account-view: No account provided");const t=s.fz.getNetworkImage(this.network);return a.dy`
      <wui-flex
        flexDirection="column"
        .padding=${["0","s","m","s"]}
        alignItems="center"
        gap="l"
      >
        <wui-avatar
          alt=${this.address}
          address=${this.address}
          imageSrc=${u(null===this.profileImage?void 0:this.profileImage)}
        ></wui-avatar>

        <wui-flex flexDirection="column" alignItems="center">
          <wui-flex gap="3xs" alignItems="center" justifyContent="center">
            <wui-text variant="large-600" color="fg-100">
              ${this.profileName?o.UiHelperUtil.getTruncateString({string:this.profileName,charsStart:20,charsEnd:0,truncate:"end"}):o.UiHelperUtil.getTruncateString({string:this.address,charsStart:4,charsEnd:6,truncate:"middle"})}
            </wui-text>
            <wui-icon-link
              size="md"
              icon="copy"
              iconColor="fg-200"
              @click=${this.onCopyAddress}
            ></wui-icon-link>
          </wui-flex>
          <wui-flex gap="s" flexDirection="column" alignItems="center">
            <wui-text variant="paragraph-500" color="fg-200">
              ${s.j1.formatBalance(this.balance,this.balanceSymbol)}
            </wui-text>

            ${this.explorerBtnTemplate()}
          </wui-flex>
        </wui-flex>
      </wui-flex>

      <wui-flex flexDirection="column" gap="xs" .padding=${["0","s","s","s"]}>
        ${this.emailCardTemplate()} ${this.emailBtnTemplate()}

        <wui-list-item
          .variant=${t?"image":"icon"}
          iconVariant="overlay"
          icon="networkPlaceholder"
          imageSrc=${u(t)}
          ?chevron=${this.isAllowedNetworkSwitch()}
          @click=${this.onNetworks.bind(this)}
          data-testid="w3m-account-select-network"
        >
          <wui-text variant="paragraph-500" color="fg-100">
            ${this.network?.name??"Unknown"}
          </wui-text>
        </wui-list-item>
        <wui-list-item
          iconVariant="blue"
          icon="swapHorizontalBold"
          iconSize="sm"
          ?chevron=${!0}
          @click=${this.onTransactions.bind(this)}
        >
          <wui-text variant="paragraph-500" color="fg-100">Activity</wui-text>
        </wui-list-item>
        <wui-list-item
          variant="icon"
          iconVariant="overlay"
          icon="disconnect"
          ?chevron=${!1}
          .loading=${this.disconecting}
          @click=${this.onDisconnect.bind(this)}
          data-testid="disconnect-button"
        >
          <wui-text variant="paragraph-500" color="fg-200">Disconnect</wui-text>
        </wui-list-item>
      </wui-flex>
    `}emailCardTemplate(){const t=s.MO.getConnectedConnector(),e=s.ConnectorController.getEmailConnector(),{origin:r}=location;return!e||"EMAIL"!==t||r.includes(s.bq.SECURE_SITE)?null:a.dy`
      <wui-notice-card
        @click=${this.onGoToUpgradeView.bind(this)}
        label="Upgrade your wallet"
        description="Transition to a non-custodial wallet"
        icon="wallet"
      ></wui-notice-card>
    `}emailBtnTemplate(){const t=s.MO.getConnectedConnector(),e=s.ConnectorController.getEmailConnector();if(!e||"EMAIL"!==t)return null;const r=e.provider.getEmail()??"";return a.dy`
      <wui-list-item
        variant="icon"
        iconVariant="overlay"
        icon="mail"
        iconSize="sm"
        ?chevron=${!0}
        @click=${()=>this.onGoToUpdateEmail(r)}
      >
        <wui-text variant="paragraph-500" color="fg-100">${r}</wui-text>
      </wui-list-item>
    `}explorerBtnTemplate(){const{addressExplorerUrl:t}=s.AccountController.state;return t?a.dy`
      <wui-button size="sm" variant="shade" @click=${this.onExplorer.bind(this)}>
        <wui-icon size="sm" color="inherit" slot="iconLeft" name="compass"></wui-icon>
        Block Explorer
        <wui-icon size="sm" color="inherit" slot="iconRight" name="externalLink"></wui-icon>
      </wui-button>
    `:null}isAllowedNetworkSwitch(){const{requestedCaipNetworks:t}=s.NetworkController.state,e=!!t&&t.length>1,r=t?.find((({id:t})=>t===this.network?.id));return e||!r}onCopyAddress(){try{this.address&&(s.j1.copyToClopboard(this.address),s.SnackController.showSuccess("Address copied"))}catch{s.SnackController.showError("Failed to copy")}}onNetworks(){this.isAllowedNetworkSwitch()&&s.RouterController.push("Networks")}onTransactions(){s.Xs.sendEvent({type:"track",event:"CLICK_TRANSACTIONS"}),s.RouterController.push("Transactions")}async onDisconnect(){try{this.disconecting=!0,await s.ConnectionController.disconnect(),s.Xs.sendEvent({type:"track",event:"DISCONNECT_SUCCESS"}),s.IN.close()}catch{s.Xs.sendEvent({type:"track",event:"DISCONNECT_ERROR"}),s.SnackController.showError("Failed to disconnect")}finally{this.disconecting=!1}}onExplorer(){const{addressExplorerUrl:t}=s.AccountController.state;t&&s.j1.openHref(t,"_blank")}onGoToUpgradeView(){s.RouterController.push("UpgradeEmailWallet")}onGoToUpdateEmail(t){s.RouterController.push("UpdateEmailWallet",{email:t})}};A.styles=x,_([(0,c.SB)()],A.prototype,"address",void 0),_([(0,c.SB)()],A.prototype,"profileImage",void 0),_([(0,c.SB)()],A.prototype,"profileName",void 0),_([(0,c.SB)()],A.prototype,"balance",void 0),_([(0,c.SB)()],A.prototype,"balanceSymbol",void 0),_([(0,c.SB)()],A.prototype,"network",void 0),_([(0,c.SB)()],A.prototype,"disconecting",void 0),A=_([(0,o.customElement)("w3m-account-view")],A);var k=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let S=class extends a.oi{constructor(){super(...arguments),this.search="",this.onDebouncedSearch=s.j1.debounce((t=>{this.search=t}))}render(){const t=this.search.length>=2;return a.dy`
      <wui-flex padding="s" gap="s">
        <wui-search-bar @inputChange=${this.onInputChange.bind(this)}></wui-search-bar>
        ${this.qrButtonTemplate()}
      </wui-flex>
      ${t?a.dy`<w3m-all-wallets-search query=${this.search}></w3m-all-wallets-search>`:a.dy`<w3m-all-wallets-list></w3m-all-wallets-list>`}
    `}onInputChange(t){this.onDebouncedSearch(t.detail)}qrButtonTemplate(){return s.j1.isMobile()?a.dy`
        <wui-icon-box
          size="lg"
          iconSize="xl"
          iconColor="accent-100"
          backgroundColor="accent-100"
          icon="qrCode"
          background="transparent"
          border
          borderColor="wui-accent-glass-010"
          @click=${this.onWalletConnectQr.bind(this)}
        ></wui-icon-box>
      `:null}onWalletConnectQr(){s.RouterController.push("ConnectingWalletConnect")}};k([(0,c.SB)()],S.prototype,"search",void 0),S=k([(0,o.customElement)("w3m-all-wallets-view")],S);const C=a.iv`
  wui-flex {
    max-height: clamp(360px, 540px, 80vh);
    overflow: scroll;
    scrollbar-width: none;
  }

  wui-flex::-webkit-scrollbar {
    display: none;
  }
`;var M=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let I=class extends a.oi{constructor(){super(),this.unsubscribe=[],this.connectors=s.ConnectorController.state.connectors,this.unsubscribe.push(s.ConnectorController.subscribeKey("connectors",(t=>this.connectors=t)))}disconnectedCallback(){this.unsubscribe.forEach((t=>t()))}render(){return a.dy`
      <wui-flex flexDirection="column" padding="s" gap="xs">
        <w3m-email-login-widget></w3m-email-login-widget>

        ${this.walletConnectConnectorTemplate()} ${this.recentTemplate()}
        ${this.announcedTemplate()} ${this.injectedTemplate()} ${this.featuredTemplate()}
        ${this.customTemplate()} ${this.recommendedTemplate()} ${this.connectorsTemplate()}
        ${this.allWalletsTemplate()}
      </wui-flex>
      <w3m-legal-footer></w3m-legal-footer>
    `}walletConnectConnectorTemplate(){if(s.j1.isMobile())return null;const t=this.connectors.find((t=>"WALLET_CONNECT"===t.type));return t?a.dy`
      <wui-list-wallet
        imageSrc=${u(s.fz.getConnectorImage(t))}
        name=${t.name??"Unknown"}
        @click=${()=>this.onConnector(t)}
        tagLabel="qr code"
        tagVariant="main"
        data-testid="wallet-selector-walletconnect"
      >
      </wui-list-wallet>
    `:null}customTemplate(){const{customWallets:t}=s.OptionsController.state;return t?.length?this.filterOutDuplicateWallets(t).map((t=>a.dy`
        <wui-list-wallet
          imageSrc=${u(s.fz.getWalletImage(t))}
          name=${t.name??"Unknown"}
          @click=${()=>this.onConnectWallet(t)}
        >
        </wui-list-wallet>
      `)):null}featuredTemplate(){if(!this.connectors.find((t=>"WALLET_CONNECT"===t.type)))return null;const{featured:t}=s.ApiController.state;return t.length?this.filterOutDuplicateWallets(t).map((t=>a.dy`
        <wui-list-wallet
          imageSrc=${u(s.fz.getWalletImage(t))}
          name=${t.name??"Unknown"}
          @click=${()=>this.onConnectWallet(t)}
        >
        </wui-list-wallet>
      `)):null}recentTemplate(){return s.MO.getRecentWallets().map((t=>a.dy`
        <wui-list-wallet
          imageSrc=${u(s.fz.getWalletImage(t))}
          name=${t.name??"Unknown"}
          @click=${()=>this.onConnectWallet(t)}
          tagLabel="recent"
          tagVariant="shade"
        >
        </wui-list-wallet>
      `))}announcedTemplate(){return this.connectors.map((t=>"ANNOUNCED"!==t.type?null:a.dy`
        <wui-list-wallet
          imageSrc=${u(s.fz.getConnectorImage(t))}
          name=${t.name??"Unknown"}
          @click=${()=>this.onConnector(t)}
          tagVariant="success"
          .installed=${!0}
        >
        </wui-list-wallet>
      `))}injectedTemplate(){const t=this.connectors.find((t=>"ANNOUNCED"===t.type));return this.connectors.map((e=>"INJECTED"!==e.type?null:s.ConnectionController.checkInstalled()?a.dy`
        <wui-list-wallet
          imageSrc=${u(s.fz.getConnectorImage(e))}
          .installed=${Boolean(t)}
          name=${e.name??"Unknown"}
          @click=${()=>this.onConnector(e)}
        >
        </wui-list-wallet>
      `:null))}connectorsTemplate(){const t=s.ConnectorController.getAnnouncedConnectorRdns();return this.connectors.map((e=>["WALLET_CONNECT","INJECTED","ANNOUNCED","EMAIL"].includes(e.type)||t.includes(s.bq.CONNECTOR_RDNS_MAP[e.id])?null:a.dy`
        <wui-list-wallet
          imageSrc=${u(s.fz.getConnectorImage(e))}
          name=${e.name??"Unknown"}
          @click=${()=>this.onConnector(e)}
        >
        </wui-list-wallet>
      `))}allWalletsTemplate(){if(!this.connectors.find((t=>"WALLET_CONNECT"===t.type)))return null;const t=s.ApiController.state.count+s.ApiController.state.featured.length,e=t<10?t:10*Math.floor(t/10),r=e<t?`${e}+`:`${e}`;return a.dy`
      <wui-list-wallet
        name="All Wallets"
        walletIcon="allWallets"
        showAllWallets
        @click=${this.onAllWallets.bind(this)}
        tagLabel=${r}
        tagVariant="shade"
        data-testid="all-wallets"
      ></wui-list-wallet>
    `}recommendedTemplate(){if(!this.connectors.find((t=>"WALLET_CONNECT"===t.type)))return null;const{recommended:t}=s.ApiController.state,{customWallets:e,featuredWalletIds:r}=s.OptionsController.state,{connectors:n}=s.ConnectorController.state,i=s.MO.getRecentWallets(),o=n.filter((t=>"ANNOUNCED"===t.type));if(r||e||!t.length)return null;const c=o.length+i.length,l=Math.max(0,2-c);return this.filterOutDuplicateWallets(t).slice(0,l).map((t=>a.dy`
        <wui-list-wallet
          imageSrc=${u(s.fz.getWalletImage(t))}
          name=${t?.name??"Unknown"}
          @click=${()=>this.onConnectWallet(t)}
        >
        </wui-list-wallet>
      `))}onConnector(t){"WALLET_CONNECT"===t.type?s.j1.isMobile()?s.RouterController.push("AllWallets"):s.RouterController.push("ConnectingWalletConnect"):s.RouterController.push("ConnectingExternal",{connector:t})}filterOutDuplicateWallets(t){const{connectors:e}=s.ConnectorController.state,r=s.MO.getRecentWallets().map((t=>t.id)),n=e.map((t=>t.info?.rdns)).filter(Boolean);return t.filter((t=>!r.includes(t.id)&&!n.includes(t.rdns??void 0)))}onAllWallets(){s.Xs.sendEvent({type:"track",event:"CLICK_ALL_WALLETS"}),s.RouterController.push("AllWallets")}onConnectWallet(t){s.RouterController.push("ConnectingWalletConnect",{wallet:t})}};I.styles=C,M([(0,c.SB)()],I.prototype,"connectors",void 0),I=M([(0,o.customElement)("w3m-connect-view")],I);const P=a.iv`
  @keyframes shake {
    0% {
      transform: translateX(0);
    }
    25% {
      transform: translateX(3px);
    }
    50% {
      transform: translateX(-3px);
    }
    75% {
      transform: translateX(3px);
    }
    100% {
      transform: translateX(0);
    }
  }

  wui-flex:first-child:not(:only-child) {
    position: relative;
  }

  wui-loading-thumbnail {
    position: absolute;
  }

  wui-icon-box {
    position: absolute;
    right: calc(var(--wui-spacing-3xs) * -1);
    bottom: calc(var(--wui-spacing-3xs) * -1);
    opacity: 0;
    transform: scale(0.5);
    transition: all var(--wui-ease-out-power-2) var(--wui-duration-lg);
  }

  wui-text[align='center'] {
    width: 100%;
    padding: 0px var(--wui-spacing-l);
  }

  [data-error='true'] wui-icon-box {
    opacity: 1;
    transform: scale(1);
  }

  [data-error='true'] > wui-flex:first-child {
    animation: shake 250ms cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
  }

  [data-retry='false'] wui-link {
    display: none;
  }

  [data-retry='true'] wui-link {
    display: block;
    opacity: 1;
  }
`;var O=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};class T extends a.oi{constructor(){super(),this.wallet=s.RouterController.state.data?.wallet,this.connector=s.RouterController.state.data?.connector,this.timeout=void 0,this.secondaryBtnLabel="Try again",this.secondaryBtnIcon="refresh",this.secondaryLabel="Accept connection request in the wallet",this.onConnect=void 0,this.onRender=void 0,this.onAutoConnect=void 0,this.isWalletConnect=!0,this.unsubscribe=[],this.imageSrc=s.fz.getWalletImage(this.wallet)??s.fz.getConnectorImage(this.connector),this.name=this.wallet?.name??this.connector?.name??"Wallet",this.isRetrying=!1,this.uri=s.ConnectionController.state.wcUri,this.error=s.ConnectionController.state.wcError,this.ready=!1,this.showRetry=!1,this.buffering=!1,this.isMobile=!1,this.onRetry=void 0,this.unsubscribe.push(s.ConnectionController.subscribeKey("wcUri",(t=>{this.uri=t,this.isRetrying&&this.onRetry&&(this.isRetrying=!1,this.onConnect?.())})),s.ConnectionController.subscribeKey("wcError",(t=>this.error=t)),s.ConnectionController.subscribeKey("buffering",(t=>this.buffering=t)))}firstUpdated(){this.onAutoConnect?.(),this.showRetry=!this.onAutoConnect}disconnectedCallback(){this.unsubscribe.forEach((t=>t())),clearTimeout(this.timeout)}render(){this.onRender?.(),this.onShowRetry();const t=this.error?"Connection can be declined if a previous request is still active":this.secondaryLabel;let e=`Continue in ${this.name}`;return this.buffering&&(e="Connecting..."),this.error&&(e="Connection declined"),a.dy`
      <wui-flex
        data-error=${u(this.error)}
        data-retry=${this.showRetry}
        flexDirection="column"
        alignItems="center"
        .padding=${["3xl","xl","xl","xl"]}
        gap="xl"
      >
        <wui-flex justifyContent="center" alignItems="center">
          <wui-wallet-image size="lg" imageSrc=${u(this.imageSrc)}></wui-wallet-image>

          ${this.error?null:this.loaderTemplate()}

          <wui-icon-box
            backgroundColor="error-100"
            background="opaque"
            iconColor="error-100"
            icon="close"
            size="sm"
            border
            borderColor="wui-color-bg-125"
          ></wui-icon-box>
        </wui-flex>

        <wui-flex flexDirection="column" alignItems="center" gap="xs">
          <wui-text variant="paragraph-500" color=${this.error?"error-100":"fg-100"}>
            ${e}
          </wui-text>
          <wui-text align="center" variant="small-500" color="fg-200">${t}</wui-text>
        </wui-flex>

        <wui-button
          variant="accent"
          ?disabled=${!this.error&&this.buffering}
          @click=${this.onTryAgain.bind(this)}
        >
          <wui-icon color="inherit" slot="iconLeft" name=${this.secondaryBtnIcon}></wui-icon>
          ${this.secondaryBtnLabel}
        </wui-button>
      </wui-flex>

      ${this.isWalletConnect?a.dy`
            <wui-flex .padding=${["0","xl","xl","xl"]} justifyContent="center">
              <wui-link @click=${this.onCopyUri} color="fg-200">
                <wui-icon size="xs" color="fg-200" slot="iconLeft" name="copy"></wui-icon>
                Copy link
              </wui-link>
            </wui-flex>
          `:null}

      <w3m-mobile-download-links .wallet=${this.wallet}></w3m-mobile-download-links>
    `}onShowRetry(){if(this.error&&!this.showRetry){this.showRetry=!0;const t=this.shadowRoot?.querySelector("wui-button");t.animate([{opacity:0},{opacity:1}],{fill:"forwards",easing:"ease"})}}onTryAgain(){this.buffering||(s.ConnectionController.setWcError(!1),this.onRetry?(this.isRetrying=!0,this.onRetry?.()):this.onConnect?.())}loaderTemplate(){const t=s.ThemeController.state.themeVariables["--w3m-border-radius-master"],e=t?parseInt(t.replace("px",""),10):4;return a.dy`<wui-loading-thumbnail radius=${9*e}></wui-loading-thumbnail>`}onCopyUri(){try{this.uri&&(s.j1.copyToClopboard(this.uri),s.SnackController.showSuccess("Link copied"))}catch{s.SnackController.showError("Failed to copy")}}}T.styles=P,O([(0,c.SB)()],T.prototype,"uri",void 0),O([(0,c.SB)()],T.prototype,"error",void 0),O([(0,c.SB)()],T.prototype,"ready",void 0),O([(0,c.SB)()],T.prototype,"showRetry",void 0),O([(0,c.SB)()],T.prototype,"buffering",void 0),O([(0,c.Cb)({type:Boolean})],T.prototype,"isMobile",void 0),O([(0,c.Cb)()],T.prototype,"onRetry",void 0);const N={INJECTED:"browser",ANNOUNCED:"browser"};let R=class extends T{constructor(){if(super(),!this.connector)throw new Error("w3m-connecting-view: No connector provided");s.Xs.sendEvent({type:"track",event:"SELECT_WALLET",properties:{name:this.connector.name??"Unknown",platform:N[this.connector.type]??"external"}}),this.onConnect=this.onConnectProxy.bind(this),this.onAutoConnect=this.onConnectProxy.bind(this),this.isWalletConnect=!1}async onConnectProxy(){try{this.error=!1,this.connector&&(this.connector.imageUrl&&s.MO.setConnectedWalletImageUrl(this.connector.imageUrl),await s.ConnectionController.connectExternal(this.connector),s.yD.state.isSiweEnabled?s.RouterController.push("ConnectingSiwe"):s.IN.close(),s.Xs.sendEvent({type:"track",event:"CONNECT_SUCCESS",properties:{method:"external"}}))}catch(t){s.Xs.sendEvent({type:"track",event:"CONNECT_ERROR",properties:{message:t?.message??"Unknown"}}),this.error=!0}}};R=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o}([(0,o.customElement)("w3m-connecting-external-view")],R);var B=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let j=class extends a.oi{constructor(){super(...arguments),this.dappName=s.OptionsController.state.metadata?.name,this.isSigning=!1}render(){return a.dy`
      <wui-flex justifyContent="center" .padding=${["2xl","0","xxl","0"]}>
        <w3m-connecting-siwe></w3m-connecting-siwe>
      </wui-flex>
      <wui-flex
        .padding=${["0","4xl","l","4xl"]}
        gap="s"
        justifyContent="space-between"
      >
        <wui-text variant="paragraph-500" align="center" color="fg-100"
          >${this.dappName??"Dapp"} needs to connect to your wallet</wui-text
        >
      </wui-flex>
      <wui-flex
        .padding=${["0","3xl","l","3xl"]}
        gap="s"
        justifyContent="space-between"
      >
        <wui-text variant="small-400" align="center" color="fg-200"
          >Sign this message to prove you own this wallet and proceed. Canceling will disconnect
          you.</wui-text
        >
      </wui-flex>
      <wui-flex .padding=${["l","xl","xl","xl"]} gap="s" justifyContent="space-between">
        <wui-button
          size="md"
          ?fullwidth=${!0}
          variant="shade"
          @click=${this.onCancel.bind(this)}
          data-testid="w3m-connecting-siwe-cancel"
        >
          Cancel
        </wui-button>
        <wui-button
          size="md"
          ?fullwidth=${!0}
          variant="fill"
          @click=${this.onSign.bind(this)}
          ?loading=${this.isSigning}
          data-testid="w3m-connecting-siwe-sign"
        >
          ${this.isSigning?"Signing...":"Sign"}
        </wui-button>
      </wui-flex>
    `}async onSign(){this.isSigning=!0,s.Xs.sendEvent({event:"CLICK_SIGN_SIWE_MESSAGE",type:"track"});try{s.yD.setStatus("loading");const t=await s.yD.signIn();return s.yD.setStatus("success"),s.Xs.sendEvent({event:"SIWE_AUTH_SUCCESS",type:"track"}),t}catch(t){return s.SnackController.showError("Signature declined"),s.yD.setStatus("error"),s.Xs.sendEvent({event:"SIWE_AUTH_ERROR",type:"track"})}finally{this.isSigning=!1}}async onCancel(){const{isConnected:t}=s.AccountController.state;t?(await s.ConnectionController.disconnect(),s.IN.close()):s.RouterController.push("Connect"),s.Xs.sendEvent({event:"CLICK_CANCEL_SIWE",type:"track"})}};B([(0,c.SB)()],j.prototype,"isSigning",void 0),j=B([(0,o.customElement)("w3m-connecting-siwe-view")],j);var L=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let U=class extends a.oi{constructor(){super(),this.interval=void 0,this.lastRetry=Date.now(),this.wallet=s.RouterController.state.data?.wallet,this.platform=void 0,this.platforms=[],this.initializeConnection(),this.interval=setInterval(this.initializeConnection.bind(this),s.bq.TEN_SEC_MS)}disconnectedCallback(){clearTimeout(this.interval)}render(){return this.wallet?(this.determinePlatforms(),a.dy`
      ${this.headerTemplate()}
      <div>${this.platformTemplate()}</div>
    `):a.dy`<w3m-connecting-wc-qrcode></w3m-connecting-wc-qrcode>`}async initializeConnection(t=!1){try{const{wcPairingExpiry:e}=s.ConnectionController.state;if(t||s.j1.isPairingExpired(e)){if(s.ConnectionController.connectWalletConnect(),this.wallet){const t=s.fz.getWalletImage(this.wallet);t&&s.MO.setConnectedWalletImageUrl(t)}else{const t=s.ConnectorController.state.connectors.find((t=>"WALLET_CONNECT"===t.type)),e=s.fz.getConnectorImage(t);e&&s.MO.setConnectedWalletImageUrl(e)}await s.ConnectionController.state.wcPromise,this.finalizeConnection(),s.yD.state.isSiweEnabled?s.RouterController.push("ConnectingSiwe"):s.IN.close()}}catch(t){s.Xs.sendEvent({type:"track",event:"CONNECT_ERROR",properties:{message:t?.message??"Unknown"}}),s.ConnectionController.setWcError(!0),s.j1.isAllowedRetry(this.lastRetry)&&(s.SnackController.showError("Declined"),this.lastRetry=Date.now(),this.initializeConnection(!0))}}finalizeConnection(){const{wcLinking:t,recentWallet:e}=s.ConnectionController.state;t&&s.MO.setWalletConnectDeepLink(t),e&&s.MO.setWeb3ModalRecent(e),s.Xs.sendEvent({type:"track",event:"CONNECT_SUCCESS",properties:{method:t?"mobile":"qrcode"}})}determinePlatforms(){if(!this.wallet)throw new Error("w3m-connecting-wc-view:determinePlatforms No wallet");if(this.platform)return;const{mobile_link:t,desktop_link:e,webapp_link:r,injected:n,rdns:i}=this.wallet,o=n?.map((({injected_id:t})=>t)).filter(Boolean),a=i?[i]:o??[],c=a.length,l=t,u=r,h=s.ConnectionController.checkInstalled(a),d=c&&h,p=e&&!s.j1.isMobile();d&&this.platforms.push("browser"),l&&this.platforms.push(s.j1.isMobile()?"mobile":"qrcode"),u&&this.platforms.push("web"),p&&this.platforms.push("desktop"),!d&&c&&this.platforms.push("unsupported"),this.platform=this.platforms[0]}platformTemplate(){switch(this.platform){case"browser":return a.dy`<w3m-connecting-wc-browser></w3m-connecting-wc-browser>`;case"desktop":return a.dy`
          <w3m-connecting-wc-desktop .onRetry=${()=>this.initializeConnection(!0)}>
          </w3m-connecting-wc-desktop>
        `;case"web":return a.dy`
          <w3m-connecting-wc-web .onRetry=${()=>this.initializeConnection(!0)}>
          </w3m-connecting-wc-web>
        `;case"mobile":return a.dy`
          <w3m-connecting-wc-mobile isMobile .onRetry=${()=>this.initializeConnection(!0)}>
          </w3m-connecting-wc-mobile>
        `;case"qrcode":return a.dy`<w3m-connecting-wc-qrcode></w3m-connecting-wc-qrcode>`;default:return a.dy`<w3m-connecting-wc-unsupported></w3m-connecting-wc-unsupported>`}}headerTemplate(){return this.platforms.length>1?a.dy`
      <w3m-connecting-header
        .platforms=${this.platforms}
        .onSelectPlatfrom=${this.onSelectPlatform.bind(this)}
      >
      </w3m-connecting-header>
    `:null}async onSelectPlatform(t){const e=this.shadowRoot?.querySelector("div");e&&(await e.animate([{opacity:1},{opacity:0}],{duration:200,fill:"forwards",easing:"ease"}).finished,this.platform=t,e.animate([{opacity:0},{opacity:1}],{duration:200,fill:"forwards",easing:"ease"}))}};L([(0,c.SB)()],U.prototype,"platform",void 0),L([(0,c.SB)()],U.prototype,"platforms",void 0),U=L([(0,o.customElement)("w3m-connecting-wc-view")],U);let D=class extends a.oi{constructor(){super(...arguments),this.wallet=s.RouterController.state.data?.wallet}render(){if(!this.wallet)throw new Error("w3m-downloads-view");return a.dy`
      <wui-flex gap="xs" flexDirection="column" .padding=${["s","s","l","s"]}>
        ${this.chromeTemplate()} ${this.iosTemplate()} ${this.androidTemplate()}
        ${this.homepageTemplate()}
      </wui-flex>
    `}chromeTemplate(){return this.wallet?.chrome_store?a.dy`<wui-list-item
      variant="icon"
      icon="chromeStore"
      iconVariant="square"
      @click=${this.onChromeStore.bind(this)}
      chevron
    >
      <wui-text variant="paragraph-500" color="fg-100">Chrome Extension</wui-text>
    </wui-list-item>`:null}iosTemplate(){return this.wallet?.app_store?a.dy`<wui-list-item
      variant="icon"
      icon="appStore"
      iconVariant="square"
      @click=${this.onAppStore.bind(this)}
      chevron
    >
      <wui-text variant="paragraph-500" color="fg-100">iOS App</wui-text>
    </wui-list-item>`:null}androidTemplate(){return this.wallet?.play_store?a.dy`<wui-list-item
      variant="icon"
      icon="playStore"
      iconVariant="square"
      @click=${this.onPlayStore.bind(this)}
      chevron
    >
      <wui-text variant="paragraph-500" color="fg-100">Android App</wui-text>
    </wui-list-item>`:null}homepageTemplate(){return this.wallet?.homepage?a.dy`
      <wui-list-item
        variant="icon"
        icon="browser"
        iconVariant="square-blue"
        @click=${this.onHomePage.bind(this)}
        chevron
      >
        <wui-text variant="paragraph-500" color="fg-100">Website</wui-text>
      </wui-list-item>
    `:null}onChromeStore(){this.wallet?.chrome_store&&s.j1.openHref(this.wallet.chrome_store,"_blank")}onAppStore(){this.wallet?.app_store&&s.j1.openHref(this.wallet.app_store,"_blank")}onPlayStore(){this.wallet?.play_store&&s.j1.openHref(this.wallet.play_store,"_blank")}onHomePage(){this.wallet?.homepage&&s.j1.openHref(this.wallet.homepage,"_blank")}};D=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o}([(0,o.customElement)("w3m-downloads-view")],D);let $=class extends a.oi{render(){return a.dy`
      <wui-flex flexDirection="column" padding="s" gap="xs">
        ${this.recommendedWalletsTemplate()}
        <wui-list-wallet
          name="Explore all"
          showAllWallets
          walletIcon="allWallets"
          icon="externalLink"
          @click=${()=>{s.j1.openHref("https://walletconnect.com/explorer?type=wallet","_blank")}}
        ></wui-list-wallet>
      </wui-flex>
    `}recommendedWalletsTemplate(){const{recommended:t,featured:e}=s.ApiController.state,{customWallets:r}=s.OptionsController.state;return[...e,...r??[],...t].slice(0,4).map((t=>a.dy`
        <wui-list-wallet
          name=${t.name??"Unknown"}
          tagVariant="main"
          imageSrc=${u(s.fz.getWalletImage(t))}
          @click=${()=>{s.j1.openHref(t.homepage??"https://walletconnect.com/explorer","_blank")}}
        ></wui-list-wallet>
      `))}};$=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o}([(0,o.customElement)("w3m-get-wallet-view")],$);const F=a.iv`
  @keyframes shake {
    0% {
      transform: translateX(0);
    }
    25% {
      transform: translateX(3px);
    }
    50% {
      transform: translateX(-3px);
    }
    75% {
      transform: translateX(3px);
    }
    100% {
      transform: translateX(0);
    }
  }

  wui-flex:first-child:not(:only-child) {
    position: relative;
  }

  wui-loading-hexagon {
    position: absolute;
  }

  wui-icon-box {
    position: absolute;
    right: 4px;
    bottom: 0;
    opacity: 0;
    transform: scale(0.5);
    z-index: 1;
    transition: all var(--wui-ease-out-power-2) var(--wui-duration-lg);
  }

  wui-button {
    display: none;
  }

  [data-error='true'] wui-icon-box {
    opacity: 1;
    transform: scale(1);
  }

  [data-error='true'] > wui-flex:first-child {
    animation: shake 250ms cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
  }

  wui-button[data-retry='true'] {
    display: block;
    opacity: 1;
  }
`;var z=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let H=class extends a.oi{constructor(){super(),this.network=s.RouterController.state.data?.network,this.unsubscribe=[],this.showRetry=!1,this.error=!1}disconnectedCallback(){this.unsubscribe.forEach((t=>t()))}firstUpdated(){this.onSwitchNetwork()}render(){if(!this.network)throw new Error("w3m-network-switch-view: No network provided");this.onShowRetry();const t=this.error?"Switch declined":"Approve in wallet",e=this.error?"Switch can be declined if chain is not supported by a wallet or previous request is still active":"Accept connection request in your wallet";return a.dy`
      <wui-flex
        data-error=${this.error}
        flexDirection="column"
        alignItems="center"
        .padding=${["3xl","xl","3xl","xl"]}
        gap="xl"
      >
        <wui-flex justifyContent="center" alignItems="center">
          <wui-network-image
            size="lg"
            imageSrc=${u(s.fz.getNetworkImage(this.network))}
          ></wui-network-image>

          ${this.error?null:a.dy`<wui-loading-hexagon></wui-loading-hexagon>`}

          <wui-icon-box
            backgroundColor="error-100"
            background="opaque"
            iconColor="error-100"
            icon="close"
            size="sm"
            ?border=${!0}
            borderColor="wui-color-bg-125"
          ></wui-icon-box>
        </wui-flex>

        <wui-flex flexDirection="column" alignItems="center" gap="xs">
          <wui-text align="center" variant="paragraph-500" color="fg-100">${t}</wui-text>
          <wui-text align="center" variant="small-500" color="fg-200">${e}</wui-text>
        </wui-flex>

        <wui-button
          data-retry=${this.showRetry}
          variant="fill"
          .disabled=${!this.error}
          @click=${this.onSwitchNetwork.bind(this)}
        >
          <wui-icon color="inherit" slot="iconLeft" name="refresh"></wui-icon>
          Try again
        </wui-button>
      </wui-flex>
    `}onShowRetry(){if(this.error&&!this.showRetry){this.showRetry=!0;const t=this.shadowRoot?.querySelector("wui-button");t.animate([{opacity:0},{opacity:1}],{fill:"forwards",easing:"ease"})}}async onSwitchNetwork(){try{this.error=!1,this.network&&(await s.NetworkController.switchActiveNetwork(this.network),s.yD.state.isSiweEnabled||s._4.navigateAfterNetworkSwitch())}catch{this.error=!0}}};H.styles=F,z([(0,c.SB)()],H.prototype,"showRetry",void 0),z([(0,c.SB)()],H.prototype,"error",void 0),H=z([(0,o.customElement)("w3m-network-switch-view")],H);const W=a.iv`
  :host > wui-grid {
    max-height: 360px;
    overflow: auto;
  }

  wui-grid::-webkit-scrollbar {
    display: none;
  }
`;var q=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let V=class extends a.oi{constructor(){super(),this.unsubscribe=[],this.caipNetwork=s.NetworkController.state.caipNetwork,this.unsubscribe.push(s.NetworkController.subscribeKey("caipNetwork",(t=>this.caipNetwork=t)))}disconnectedCallback(){this.unsubscribe.forEach((t=>t()))}render(){return a.dy`
      <wui-grid padding="s" gridTemplateColumns="repeat(4, 1fr)" rowGap="l" columnGap="xs">
        ${this.networksTemplate()}
      </wui-grid>

      <wui-separator></wui-separator>

      <wui-flex padding="s" flexDirection="column" gap="m" alignItems="center">
        <wui-text variant="small-400" color="fg-300" align="center">
          Your connected wallet may not support some of the networks available for this dApp
        </wui-text>
        <wui-link @click=${this.onNetworkHelp.bind(this)}>
          <wui-icon size="xs" color="accent-100" slot="iconLeft" name="helpCircle"></wui-icon>
          What is a network
        </wui-link>
      </wui-flex>
    `}onNetworkHelp(){s.Xs.sendEvent({type:"track",event:"CLICK_NETWORK_HELP"}),s.RouterController.push("WhatIsANetwork")}networksTemplate(){const{approvedCaipNetworkIds:t,requestedCaipNetworks:e,supportsAllNetworks:r}=s.NetworkController.state,n=t,i=e,o={};return i&&n&&(n.forEach(((t,e)=>{o[t]=e})),i.sort(((t,e)=>{const r=o[t.id],n=o[e.id];return void 0!==r&&void 0!==n?r-n:void 0!==r?-1:void 0!==n?1:0}))),i?.map((t=>a.dy`
        <wui-card-select
          .selected=${this.caipNetwork?.id===t.id}
          imageSrc=${u(s.fz.getNetworkImage(t))}
          type="network"
          name=${t.name??t.id}
          @click=${()=>this.onSwitchNetwork(t)}
          .disabled=${!r&&!n?.includes(t.id)}
          data-testid=${`w3m-network-switch-${t.name??t.id}`}
        ></wui-card-select>
      `))}async onSwitchNetwork(t){const{isConnected:e}=s.AccountController.state,{approvedCaipNetworkIds:r,supportsAllNetworks:n,caipNetwork:i}=s.NetworkController.state,{data:o}=s.RouterController.state;e&&i?.id!==t.id?r?.includes(t.id)?(await s.NetworkController.switchActiveNetwork(t),s._4.navigateAfterNetworkSwitch()):n&&s.RouterController.push("SwitchNetwork",{...o,network:t}):e||(s.NetworkController.setCaipNetwork(t),s.RouterController.push("Connect"))}};V.styles=W,q([(0,c.SB)()],V.prototype,"caipNetwork",void 0),V=q([(0,o.customElement)("w3m-networks-view")],V);var G=r(10248);const K=a.iv`
  :host > wui-flex:first-child {
    height: 500px;
    overflow-y: auto;
    overflow-x: hidden;
    scrollbar-width: none;
  }
`;var Z=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};const J="last-transaction";let Q=class extends a.oi{constructor(){super(),this.unsubscribe=[],this.paginationObserver=void 0,this.address=s.AccountController.state.address,this.transactions=s.sl.state.transactions,this.transactionsByYear=s.sl.state.transactionsByYear,this.loading=s.sl.state.loading,this.empty=s.sl.state.empty,this.next=s.sl.state.next,this.unsubscribe.push(s.AccountController.subscribe((t=>{t.isConnected&&this.address!==t.address&&(this.address=t.address,s.sl.resetTransactions(),s.sl.fetchTransactions(t.address))})),s.sl.subscribe((t=>{this.transactions=t.transactions,this.transactionsByYear=t.transactionsByYear,this.loading=t.loading,this.empty=t.empty,this.next=t.next})))}firstUpdated(){0===this.transactions.length&&s.sl.fetchTransactions(this.address),this.createPaginationObserver()}updated(){this.setPaginationObserver()}disconnectedCallback(){this.unsubscribe.forEach((t=>t()))}render(){return a.dy`
      <wui-flex flexDirection="column" padding="s" gap="s">
        ${this.empty?null:this.templateTransactionsByYear()}
        ${this.loading?this.templateLoading():null}
        ${!this.loading&&this.empty?this.templateEmpty():null}
      </wui-flex>
    `}templateTransactionsByYear(){const t=Object.keys(this.transactionsByYear).sort().reverse();return t.map(((e,r)=>{const n=r===t.length-1,i=parseInt(e,10),s=o.TransactionUtil.getTransactionGroupTitle(i),c=this.transactionsByYear[i];return c?a.dy`
        <wui-flex flexDirection="column" gap="s">
          <wui-flex
            alignItems="center"
            flexDirection="row"
            .padding=${["xs","s","s","s"]}
          >
            <wui-text variant="paragraph-500" color="fg-200">${s}</wui-text>
          </wui-flex>
          <wui-flex flexDirection="column" gap="xs">
            ${this.templateTransactions(c,n)}
          </wui-flex>
        </wui-flex>
      `:null}))}templateRenderTransaction(t,e){const{date:r,descriptions:n,direction:i,isAllNFT:s,images:c,status:l,transfers:u,type:h}=this.getTransactionListItemProps(t),d=u?.length>1;return 2!==u?.length||s?d?u.map(((t,n)=>{const i=o.TransactionUtil.getTransferDescription(t),s=e&&n===u.length-1;return a.dy` <wui-transaction-list-item
          date=${r}
          direction=${t.direction}
          id=${s&&this.next?J:""}
          status=${l}
          type=${h}
          .onlyDirectionIcon=${!0}
          .images=${[c?.[n]]}
          .descriptions=${[i]}
        ></wui-transaction-list-item>`})):a.dy`
      <wui-transaction-list-item
        date=${r}
        .direction=${i}
        id=${e&&this.next?J:""}
        status=${l}
        type=${h}
        .images=${c}
        .descriptions=${n}
      ></wui-transaction-list-item>
    `:a.dy`
        <wui-transaction-list-item
          date=${r}
          .direction=${i}
          id=${e&&this.next?J:""}
          status=${l}
          type=${h}
          .images=${c}
          .descriptions=${n}
        ></wui-transaction-list-item>
      `}templateTransactions(t,e){return t.map(((r,n)=>{const i=e&&n===t.length-1;return a.dy`${this.templateRenderTransaction(r,i)}`}))}templateEmpty(){return a.dy`
      <wui-flex
        flexGrow="1"
        flexDirection="column"
        justifyContent="center"
        alignItems="center"
        .padding=${["3xl","xl","3xl","xl"]}
        gap="xl"
      >
        <wui-icon-box
          backgroundColor="glass-005"
          background="gray"
          iconColor="fg-200"
          icon="wallet"
          size="lg"
          ?border=${!0}
          borderColor="wui-color-bg-125"
        ></wui-icon-box>
        <wui-flex flexDirection="column" alignItems="center" gap="xs">
          <wui-text align="center" variant="paragraph-500" color="fg-100"
            >No Transactions yet</wui-text
          >
          <wui-text align="center" variant="small-500" color="fg-200"
            >Start trading on dApps <br />
            to grow your wallet!</wui-text
          >
        </wui-flex>
      </wui-flex>
    `}templateLoading(){return Array(7).fill(a.dy` <wui-transaction-list-item-loader></wui-transaction-list-item-loader> `).map((t=>t))}createPaginationObserver(){const{projectId:t}=s.OptionsController.state;this.paginationObserver=new IntersectionObserver((([e])=>{e?.isIntersecting&&!this.loading&&(s.sl.fetchTransactions(this.address),s.Xs.sendEvent({type:"track",event:"LOAD_MORE_TRANSACTIONS",properties:{address:this.address,projectId:t,cursor:this.next}}))}),{}),this.setPaginationObserver()}setPaginationObserver(){this.paginationObserver?.disconnect();const t=this.shadowRoot?.querySelector(`#${J}`);t&&this.paginationObserver?.observe(t)}getTransactionListItemProps(t){const e=G.E.getRelativeDateFromNow(t?.metadata?.minedAt),r=o.TransactionUtil.getTransactionDescriptions(t),n=t?.transfers,i=t?.transfers?.[0],s=Boolean(i)&&t?.transfers?.every((t=>Boolean(t.nft_info))),a=o.TransactionUtil.getTransactionImages(n);return{date:e,direction:i?.direction,descriptions:r,isAllNFT:s,images:a,status:t.metadata?.status,transfers:n,type:t.metadata?.operationType}}};Q.styles=K,Z([(0,c.SB)()],Q.prototype,"address",void 0),Z([(0,c.SB)()],Q.prototype,"transactions",void 0),Z([(0,c.SB)()],Q.prototype,"transactionsByYear",void 0),Z([(0,c.SB)()],Q.prototype,"loading",void 0),Z([(0,c.SB)()],Q.prototype,"empty",void 0),Z([(0,c.SB)()],Q.prototype,"next",void 0),Q=Z([(0,o.customElement)("w3m-transactions-view")],Q);const Y=[{images:["network","layers","system"],title:"The system’s nuts and bolts",text:"A network is what brings the blockchain to life, as this technical infrastructure allows apps to access the ledger and smart contract services."},{images:["noun","defiAlt","dao"],title:"Designed for different uses",text:"Each network is designed differently, and may therefore suit certain apps and experiences."}];let X=class extends a.oi{render(){return a.dy`
      <wui-flex
        flexDirection="column"
        .padding=${["xxl","xl","xl","xl"]}
        alignItems="center"
        gap="xl"
      >
        <w3m-help-widget .data=${Y}></w3m-help-widget>
        <wui-button
          variant="fill"
          size="sm"
          @click=${()=>{s.j1.openHref("https://ethereum.org/en/developers/docs/networks/","_blank")}}
        >
          Learn more
          <wui-icon color="inherit" slot="iconRight" name="externalLink"></wui-icon>
        </wui-button>
      </wui-flex>
    `}};X=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o}([(0,o.customElement)("w3m-what-is-a-network-view")],X);const tt=[{images:["login","profile","lock"],title:"One login for all of web3",text:"Log in to any app by connecting your wallet. Say goodbye to countless passwords!"},{images:["defi","nft","eth"],title:"A home for your digital assets",text:"A wallet lets you store, send and receive digital assets like cryptocurrencies and NFTs."},{images:["browser","noun","dao"],title:"Your gateway to a new web",text:"With your wallet, you can explore and interact with DeFi, NFTs, DAOs, and much more."}];let et=class extends a.oi{render(){return a.dy`
      <wui-flex
        flexDirection="column"
        .padding=${["xxl","xl","xl","xl"]}
        alignItems="center"
        gap="xl"
      >
        <w3m-help-widget .data=${tt}></w3m-help-widget>
        <wui-button variant="fill" size="sm" @click=${this.onGetWallet.bind(this)}>
          <wui-icon color="inherit" slot="iconLeft" name="wallet"></wui-icon>
          Get a wallet
        </wui-button>
      </wui-flex>
    `}onGetWallet(){s.Xs.sendEvent({type:"track",event:"CLICK_GET_WALLET"}),s.RouterController.push("GetWallet")}};et=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o}([(0,o.customElement)("w3m-what-is-a-wallet-view")],et);const rt=a.iv`
  wui-loading-spinner {
    margin: 9px auto;
  }
`,nt={SECURE_SITE_SDK:"https://secure.web3modal.com/sdk",APP_EVENT_KEY:"@w3m-app/",FRAME_EVENT_KEY:"@w3m-frame/",RPC_METHOD_KEY:"RPC_",STORAGE_KEY:"@w3m-storage/",SESSION_TOKEN_KEY:"SESSION_TOKEN_KEY",EMAIL_LOGIN_USED_KEY:"EMAIL_LOGIN_USED_KEY",LAST_EMAIL_LOGIN_TIME:"LAST_EMAIL_LOGIN_TIME",EMAIL:"EMAIL",APP_SWITCH_NETWORK:"@w3m-app/SWITCH_NETWORK",APP_CONNECT_EMAIL:"@w3m-app/CONNECT_EMAIL",APP_CONNECT_DEVICE:"@w3m-app/CONNECT_DEVICE",APP_CONNECT_OTP:"@w3m-app/CONNECT_OTP",APP_GET_USER:"@w3m-app/GET_USER",APP_SIGN_OUT:"@w3m-app/SIGN_OUT",APP_IS_CONNECTED:"@w3m-app/IS_CONNECTED",APP_GET_CHAIN_ID:"@w3m-app/GET_CHAIN_ID",APP_RPC_REQUEST:"@w3m-app/RPC_REQUEST",APP_UPDATE_EMAIL:"@w3m-app/UPDATE_EMAIL",APP_AWAIT_UPDATE_EMAIL:"@w3m-app/AWAIT_UPDATE_EMAIL",APP_SYNC_THEME:"@w3m-app/SYNC_THEME",FRAME_SWITCH_NETWORK_ERROR:"@w3m-frame/SWITCH_NETWORK_ERROR",FRAME_SWITCH_NETWORK_SUCCESS:"@w3m-frame/SWITCH_NETWORK_SUCCESS",FRAME_CONNECT_EMAIL_ERROR:"@w3m-frame/CONNECT_EMAIL_ERROR",FRAME_CONNECT_EMAIL_SUCCESS:"@w3m-frame/CONNECT_EMAIL_SUCCESS",FRAME_CONNECT_DEVICE_ERROR:"@w3m-frame/CONNECT_DEVICE_ERROR",FRAME_CONNECT_DEVICE_SUCCESS:"@w3m-frame/CONNECT_DEVICE_SUCCESS",FRAME_CONNECT_OTP_SUCCESS:"@w3m-frame/CONNECT_OTP_SUCCESS",FRAME_CONNECT_OTP_ERROR:"@w3m-frame/CONNECT_OTP_ERROR",FRAME_GET_USER_SUCCESS:"@w3m-frame/GET_USER_SUCCESS",FRAME_GET_USER_ERROR:"@w3m-frame/GET_USER_ERROR",FRAME_SIGN_OUT_SUCCESS:"@w3m-frame/SIGN_OUT_SUCCESS",FRAME_SIGN_OUT_ERROR:"@w3m-frame/SIGN_OUT_ERROR",FRAME_IS_CONNECTED_SUCCESS:"@w3m-frame/IS_CONNECTED_SUCCESS",FRAME_IS_CONNECTED_ERROR:"@w3m-frame/IS_CONNECTED_ERROR",FRAME_GET_CHAIN_ID_SUCCESS:"@w3m-frame/GET_CHAIN_ID_SUCCESS",FRAME_GET_CHAIN_ID_ERROR:"@w3m-frame/GET_CHAIN_ID_ERROR",FRAME_RPC_REQUEST_SUCCESS:"@w3m-frame/RPC_REQUEST_SUCCESS",FRAME_RPC_REQUEST_ERROR:"@w3m-frame/RPC_REQUEST_ERROR",FRAME_SESSION_UPDATE:"@w3m-frame/SESSION_UPDATE",FRAME_UPDATE_EMAIL_SUCCESS:"@w3m-frame/UPDATE_EMAIL_SUCCESS",FRAME_UPDATE_EMAIL_ERROR:"@w3m-frame/UPDATE_EMAIL_ERROR",FRAME_AWAIT_UPDATE_EMAIL_SUCCESS:"@w3m-frame/AWAIT_UPDATE_EMAIL_SUCCESS",FRAME_AWAIT_UPDATE_EMAIL_ERROR:"@w3m-frame/AWAIT_UPDATE_EMAIL_ERROR",FRAME_SYNC_THEME_SUCCESS:"@w3m-frame/SYNC_THEME_SUCCESS",FRAME_SYNC_THEME_ERROR:"@w3m-frame/SYNC_THEME_ERROR"};var it,st;!function(t){t.assertEqual=t=>t,t.assertIs=function(t){},t.assertNever=function(t){throw new Error},t.arrayToEnum=t=>{const e={};for(const r of t)e[r]=r;return e},t.getValidEnumValues=e=>{const r=t.objectKeys(e).filter((t=>"number"!=typeof e[e[t]])),n={};for(const t of r)n[t]=e[t];return t.objectValues(n)},t.objectValues=e=>t.objectKeys(e).map((function(t){return e[t]})),t.objectKeys="function"==typeof Object.keys?t=>Object.keys(t):t=>{const e=[];for(const r in t)Object.prototype.hasOwnProperty.call(t,r)&&e.push(r);return e},t.find=(t,e)=>{for(const r of t)if(e(r))return r},t.isInteger="function"==typeof Number.isInteger?t=>Number.isInteger(t):t=>"number"==typeof t&&isFinite(t)&&Math.floor(t)===t,t.joinValues=function(t,e=" | "){return t.map((t=>"string"==typeof t?`'${t}'`:t)).join(e)},t.jsonStringifyReplacer=(t,e)=>"bigint"==typeof e?e.toString():e}(it||(it={})),function(t){t.mergeShapes=(t,e)=>({...t,...e})}(st||(st={}));const ot=it.arrayToEnum(["string","nan","number","integer","float","boolean","date","bigint","symbol","function","undefined","null","array","object","unknown","promise","void","never","map","set"]),at=t=>{switch(typeof t){case"undefined":return ot.undefined;case"string":return ot.string;case"number":return isNaN(t)?ot.nan:ot.number;case"boolean":return ot.boolean;case"function":return ot.function;case"bigint":return ot.bigint;case"symbol":return ot.symbol;case"object":return Array.isArray(t)?ot.array:null===t?ot.null:t.then&&"function"==typeof t.then&&t.catch&&"function"==typeof t.catch?ot.promise:"undefined"!=typeof Map&&t instanceof Map?ot.map:"undefined"!=typeof Set&&t instanceof Set?ot.set:"undefined"!=typeof Date&&t instanceof Date?ot.date:ot.object;default:return ot.unknown}},ct=it.arrayToEnum(["invalid_type","invalid_literal","custom","invalid_union","invalid_union_discriminator","invalid_enum_value","unrecognized_keys","invalid_arguments","invalid_return_type","invalid_date","invalid_string","too_small","too_big","invalid_intersection_types","not_multiple_of","not_finite"]);class lt extends Error{constructor(t){super(),this.issues=[],this.addIssue=t=>{this.issues=[...this.issues,t]},this.addIssues=(t=[])=>{this.issues=[...this.issues,...t]};const e=new.target.prototype;Object.setPrototypeOf?Object.setPrototypeOf(this,e):this.__proto__=e,this.name="ZodError",this.issues=t}get errors(){return this.issues}format(t){const e=t||function(t){return t.message},r={_errors:[]},n=t=>{for(const i of t.issues)if("invalid_union"===i.code)i.unionErrors.map(n);else if("invalid_return_type"===i.code)n(i.returnTypeError);else if("invalid_arguments"===i.code)n(i.argumentsError);else if(0===i.path.length)r._errors.push(e(i));else{let t=r,n=0;for(;n<i.path.length;){const r=i.path[n];n===i.path.length-1?(t[r]=t[r]||{_errors:[]},t[r]._errors.push(e(i))):t[r]=t[r]||{_errors:[]},t=t[r],n++}}};return n(this),r}toString(){return this.message}get message(){return JSON.stringify(this.issues,it.jsonStringifyReplacer,2)}get isEmpty(){return 0===this.issues.length}flatten(t=(t=>t.message)){const e={},r=[];for(const n of this.issues)n.path.length>0?(e[n.path[0]]=e[n.path[0]]||[],e[n.path[0]].push(t(n))):r.push(t(n));return{formErrors:r,fieldErrors:e}}get formErrors(){return this.flatten()}}lt.create=t=>new lt(t);const ut=(t,e)=>{let r;switch(t.code){case ct.invalid_type:r=t.received===ot.undefined?"Required":`Expected ${t.expected}, received ${t.received}`;break;case ct.invalid_literal:r=`Invalid literal value, expected ${JSON.stringify(t.expected,it.jsonStringifyReplacer)}`;break;case ct.unrecognized_keys:r=`Unrecognized key(s) in object: ${it.joinValues(t.keys,", ")}`;break;case ct.invalid_union:r="Invalid input";break;case ct.invalid_union_discriminator:r=`Invalid discriminator value. Expected ${it.joinValues(t.options)}`;break;case ct.invalid_enum_value:r=`Invalid enum value. Expected ${it.joinValues(t.options)}, received '${t.received}'`;break;case ct.invalid_arguments:r="Invalid function arguments";break;case ct.invalid_return_type:r="Invalid function return type";break;case ct.invalid_date:r="Invalid date";break;case ct.invalid_string:"object"==typeof t.validation?"includes"in t.validation?(r=`Invalid input: must include "${t.validation.includes}"`,"number"==typeof t.validation.position&&(r=`${r} at one or more positions greater than or equal to ${t.validation.position}`)):"startsWith"in t.validation?r=`Invalid input: must start with "${t.validation.startsWith}"`:"endsWith"in t.validation?r=`Invalid input: must end with "${t.validation.endsWith}"`:it.assertNever(t.validation):r="regex"!==t.validation?`Invalid ${t.validation}`:"Invalid";break;case ct.too_small:r="array"===t.type?`Array must contain ${t.exact?"exactly":t.inclusive?"at least":"more than"} ${t.minimum} element(s)`:"string"===t.type?`String must contain ${t.exact?"exactly":t.inclusive?"at least":"over"} ${t.minimum} character(s)`:"number"===t.type?`Number must be ${t.exact?"exactly equal to ":t.inclusive?"greater than or equal to ":"greater than "}${t.minimum}`:"date"===t.type?`Date must be ${t.exact?"exactly equal to ":t.inclusive?"greater than or equal to ":"greater than "}${new Date(Number(t.minimum))}`:"Invalid input";break;case ct.too_big:r="array"===t.type?`Array must contain ${t.exact?"exactly":t.inclusive?"at most":"less than"} ${t.maximum} element(s)`:"string"===t.type?`String must contain ${t.exact?"exactly":t.inclusive?"at most":"under"} ${t.maximum} character(s)`:"number"===t.type?`Number must be ${t.exact?"exactly":t.inclusive?"less than or equal to":"less than"} ${t.maximum}`:"bigint"===t.type?`BigInt must be ${t.exact?"exactly":t.inclusive?"less than or equal to":"less than"} ${t.maximum}`:"date"===t.type?`Date must be ${t.exact?"exactly":t.inclusive?"smaller than or equal to":"smaller than"} ${new Date(Number(t.maximum))}`:"Invalid input";break;case ct.custom:r="Invalid input";break;case ct.invalid_intersection_types:r="Intersection results could not be merged";break;case ct.not_multiple_of:r=`Number must be a multiple of ${t.multipleOf}`;break;case ct.not_finite:r="Number must be finite";break;default:r=e.defaultError,it.assertNever(t)}return{message:r}};let ht=ut;function dt(){return ht}const pt=t=>{const{data:e,path:r,errorMaps:n,issueData:i}=t,s=[...r,...i.path||[]],o={...i,path:s};let a="";const c=n.filter((t=>!!t)).slice().reverse();for(const t of c)a=t(o,{data:e,defaultError:a}).message;return{...i,path:s,message:i.message||a}};function ft(t,e){const r=pt({issueData:e,data:t.data,path:t.path,errorMaps:[t.common.contextualErrorMap,t.schemaErrorMap,dt(),ut].filter((t=>!!t))});t.common.issues.push(r)}class mt{constructor(){this.value="valid"}dirty(){"valid"===this.value&&(this.value="dirty")}abort(){"aborted"!==this.value&&(this.value="aborted")}static mergeArray(t,e){const r=[];for(const n of e){if("aborted"===n.status)return gt;"dirty"===n.status&&t.dirty(),r.push(n.value)}return{status:t.value,value:r}}static async mergeObjectAsync(t,e){const r=[];for(const t of e)r.push({key:await t.key,value:await t.value});return mt.mergeObjectSync(t,r)}static mergeObjectSync(t,e){const r={};for(const n of e){const{key:e,value:i}=n;if("aborted"===e.status)return gt;if("aborted"===i.status)return gt;"dirty"===e.status&&t.dirty(),"dirty"===i.status&&t.dirty(),"__proto__"===e.value||void 0===i.value&&!n.alwaysSet||(r[e.value]=i.value)}return{status:t.value,value:r}}}const gt=Object.freeze({status:"aborted"}),yt=t=>({status:"dirty",value:t}),wt=t=>({status:"valid",value:t}),bt=t=>"aborted"===t.status,vt=t=>"dirty"===t.status,Et=t=>"valid"===t.status,xt=t=>"undefined"!=typeof Promise&&t instanceof Promise;var _t;!function(t){t.errToObj=t=>"string"==typeof t?{message:t}:t||{},t.toString=t=>"string"==typeof t?t:null==t?void 0:t.message}(_t||(_t={}));class At{constructor(t,e,r,n){this._cachedPath=[],this.parent=t,this.data=e,this._path=r,this._key=n}get path(){return this._cachedPath.length||(this._key instanceof Array?this._cachedPath.push(...this._path,...this._key):this._cachedPath.push(...this._path,this._key)),this._cachedPath}}const kt=(t,e)=>{if(Et(e))return{success:!0,data:e.value};if(!t.common.issues.length)throw new Error("Validation failed but no issues detected.");return{success:!1,get error(){if(this._error)return this._error;const e=new lt(t.common.issues);return this._error=e,this._error}}};function St(t){if(!t)return{};const{errorMap:e,invalid_type_error:r,required_error:n,description:i}=t;if(e&&(r||n))throw new Error('Can\'t use "invalid_type_error" or "required_error" in conjunction with custom error map.');return e?{errorMap:e,description:i}:{errorMap:(t,e)=>"invalid_type"!==t.code?{message:e.defaultError}:void 0===e.data?{message:null!=n?n:e.defaultError}:{message:null!=r?r:e.defaultError},description:i}}class Ct{constructor(t){this.spa=this.safeParseAsync,this._def=t,this.parse=this.parse.bind(this),this.safeParse=this.safeParse.bind(this),this.parseAsync=this.parseAsync.bind(this),this.safeParseAsync=this.safeParseAsync.bind(this),this.spa=this.spa.bind(this),this.refine=this.refine.bind(this),this.refinement=this.refinement.bind(this),this.superRefine=this.superRefine.bind(this),this.optional=this.optional.bind(this),this.nullable=this.nullable.bind(this),this.nullish=this.nullish.bind(this),this.array=this.array.bind(this),this.promise=this.promise.bind(this),this.or=this.or.bind(this),this.and=this.and.bind(this),this.transform=this.transform.bind(this),this.brand=this.brand.bind(this),this.default=this.default.bind(this),this.catch=this.catch.bind(this),this.describe=this.describe.bind(this),this.pipe=this.pipe.bind(this),this.readonly=this.readonly.bind(this),this.isNullable=this.isNullable.bind(this),this.isOptional=this.isOptional.bind(this)}get description(){return this._def.description}_getType(t){return at(t.data)}_getOrReturnCtx(t,e){return e||{common:t.parent.common,data:t.data,parsedType:at(t.data),schemaErrorMap:this._def.errorMap,path:t.path,parent:t.parent}}_processInputParams(t){return{status:new mt,ctx:{common:t.parent.common,data:t.data,parsedType:at(t.data),schemaErrorMap:this._def.errorMap,path:t.path,parent:t.parent}}}_parseSync(t){const e=this._parse(t);if(xt(e))throw new Error("Synchronous parse encountered promise.");return e}_parseAsync(t){const e=this._parse(t);return Promise.resolve(e)}parse(t,e){const r=this.safeParse(t,e);if(r.success)return r.data;throw r.error}safeParse(t,e){var r;const n={common:{issues:[],async:null!==(r=null==e?void 0:e.async)&&void 0!==r&&r,contextualErrorMap:null==e?void 0:e.errorMap},path:(null==e?void 0:e.path)||[],schemaErrorMap:this._def.errorMap,parent:null,data:t,parsedType:at(t)},i=this._parseSync({data:t,path:n.path,parent:n});return kt(n,i)}async parseAsync(t,e){const r=await this.safeParseAsync(t,e);if(r.success)return r.data;throw r.error}async safeParseAsync(t,e){const r={common:{issues:[],contextualErrorMap:null==e?void 0:e.errorMap,async:!0},path:(null==e?void 0:e.path)||[],schemaErrorMap:this._def.errorMap,parent:null,data:t,parsedType:at(t)},n=this._parse({data:t,path:r.path,parent:r}),i=await(xt(n)?n:Promise.resolve(n));return kt(r,i)}refine(t,e){const r=t=>"string"==typeof e||void 0===e?{message:e}:"function"==typeof e?e(t):e;return this._refinement(((e,n)=>{const i=t(e),s=()=>n.addIssue({code:ct.custom,...r(e)});return"undefined"!=typeof Promise&&i instanceof Promise?i.then((t=>!!t||(s(),!1))):!!i||(s(),!1)}))}refinement(t,e){return this._refinement(((r,n)=>!!t(r)||(n.addIssue("function"==typeof e?e(r,n):e),!1)))}_refinement(t){return new fe({schema:this,typeName:Se.ZodEffects,effect:{type:"refinement",refinement:t}})}superRefine(t){return this._refinement(t)}optional(){return me.create(this,this._def)}nullable(){return ge.create(this,this._def)}nullish(){return this.nullable().optional()}array(){return Zt.create(this,this._def)}promise(){return pe.create(this,this._def)}or(t){return Yt.create([this,t],this._def)}and(t){return re.create(this,t,this._def)}transform(t){return new fe({...St(this._def),schema:this,typeName:Se.ZodEffects,effect:{type:"transform",transform:t}})}default(t){const e="function"==typeof t?t:()=>t;return new ye({...St(this._def),innerType:this,defaultValue:e,typeName:Se.ZodDefault})}brand(){return new Ee({typeName:Se.ZodBranded,type:this,...St(this._def)})}catch(t){const e="function"==typeof t?t:()=>t;return new we({...St(this._def),innerType:this,catchValue:e,typeName:Se.ZodCatch})}describe(t){return new(0,this.constructor)({...this._def,description:t})}pipe(t){return xe.create(this,t)}readonly(){return _e.create(this)}isOptional(){return this.safeParse(void 0).success}isNullable(){return this.safeParse(null).success}}const Mt=/^c[^\s-]{8,}$/i,It=/^[a-z][a-z0-9]*$/,Pt=/^[0-9A-HJKMNP-TV-Z]{26}$/,Ot=/^[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12}$/i,Tt=/^(?!\.)(?!.*\.\.)([A-Z0-9_+-\.]*)[A-Z0-9_+-]@([A-Z0-9][A-Z0-9\-]*\.)+[A-Z]{2,}$/i;let Nt;const Rt=/^(((25[0-5])|(2[0-4][0-9])|(1[0-9]{2})|([0-9]{1,2}))\.){3}((25[0-5])|(2[0-4][0-9])|(1[0-9]{2})|([0-9]{1,2}))$/,Bt=/^(([a-f0-9]{1,4}:){7}|::([a-f0-9]{1,4}:){0,6}|([a-f0-9]{1,4}:){1}:([a-f0-9]{1,4}:){0,5}|([a-f0-9]{1,4}:){2}:([a-f0-9]{1,4}:){0,4}|([a-f0-9]{1,4}:){3}:([a-f0-9]{1,4}:){0,3}|([a-f0-9]{1,4}:){4}:([a-f0-9]{1,4}:){0,2}|([a-f0-9]{1,4}:){5}:([a-f0-9]{1,4}:){0,1})([a-f0-9]{1,4}|(((25[0-5])|(2[0-4][0-9])|(1[0-9]{2})|([0-9]{1,2}))\.){3}((25[0-5])|(2[0-4][0-9])|(1[0-9]{2})|([0-9]{1,2})))$/;class jt extends Ct{_parse(t){if(this._def.coerce&&(t.data=String(t.data)),this._getType(t)!==ot.string){const e=this._getOrReturnCtx(t);return ft(e,{code:ct.invalid_type,expected:ot.string,received:e.parsedType}),gt}const e=new mt;let r;for(const o of this._def.checks)if("min"===o.kind)t.data.length<o.value&&(r=this._getOrReturnCtx(t,r),ft(r,{code:ct.too_small,minimum:o.value,type:"string",inclusive:!0,exact:!1,message:o.message}),e.dirty());else if("max"===o.kind)t.data.length>o.value&&(r=this._getOrReturnCtx(t,r),ft(r,{code:ct.too_big,maximum:o.value,type:"string",inclusive:!0,exact:!1,message:o.message}),e.dirty());else if("length"===o.kind){const n=t.data.length>o.value,i=t.data.length<o.value;(n||i)&&(r=this._getOrReturnCtx(t,r),n?ft(r,{code:ct.too_big,maximum:o.value,type:"string",inclusive:!0,exact:!0,message:o.message}):i&&ft(r,{code:ct.too_small,minimum:o.value,type:"string",inclusive:!0,exact:!0,message:o.message}),e.dirty())}else if("email"===o.kind)Tt.test(t.data)||(r=this._getOrReturnCtx(t,r),ft(r,{validation:"email",code:ct.invalid_string,message:o.message}),e.dirty());else if("emoji"===o.kind)Nt||(Nt=new RegExp("^(\\p{Extended_Pictographic}|\\p{Emoji_Component})+$","u")),Nt.test(t.data)||(r=this._getOrReturnCtx(t,r),ft(r,{validation:"emoji",code:ct.invalid_string,message:o.message}),e.dirty());else if("uuid"===o.kind)Ot.test(t.data)||(r=this._getOrReturnCtx(t,r),ft(r,{validation:"uuid",code:ct.invalid_string,message:o.message}),e.dirty());else if("cuid"===o.kind)Mt.test(t.data)||(r=this._getOrReturnCtx(t,r),ft(r,{validation:"cuid",code:ct.invalid_string,message:o.message}),e.dirty());else if("cuid2"===o.kind)It.test(t.data)||(r=this._getOrReturnCtx(t,r),ft(r,{validation:"cuid2",code:ct.invalid_string,message:o.message}),e.dirty());else if("ulid"===o.kind)Pt.test(t.data)||(r=this._getOrReturnCtx(t,r),ft(r,{validation:"ulid",code:ct.invalid_string,message:o.message}),e.dirty());else if("url"===o.kind)try{new URL(t.data)}catch(n){r=this._getOrReturnCtx(t,r),ft(r,{validation:"url",code:ct.invalid_string,message:o.message}),e.dirty()}else"regex"===o.kind?(o.regex.lastIndex=0,o.regex.test(t.data)||(r=this._getOrReturnCtx(t,r),ft(r,{validation:"regex",code:ct.invalid_string,message:o.message}),e.dirty())):"trim"===o.kind?t.data=t.data.trim():"includes"===o.kind?t.data.includes(o.value,o.position)||(r=this._getOrReturnCtx(t,r),ft(r,{code:ct.invalid_string,validation:{includes:o.value,position:o.position},message:o.message}),e.dirty()):"toLowerCase"===o.kind?t.data=t.data.toLowerCase():"toUpperCase"===o.kind?t.data=t.data.toUpperCase():"startsWith"===o.kind?t.data.startsWith(o.value)||(r=this._getOrReturnCtx(t,r),ft(r,{code:ct.invalid_string,validation:{startsWith:o.value},message:o.message}),e.dirty()):"endsWith"===o.kind?t.data.endsWith(o.value)||(r=this._getOrReturnCtx(t,r),ft(r,{code:ct.invalid_string,validation:{endsWith:o.value},message:o.message}),e.dirty()):"datetime"===o.kind?((s=o).precision?s.offset?new RegExp(`^\\d{4}-\\d{2}-\\d{2}T\\d{2}:\\d{2}:\\d{2}\\.\\d{${s.precision}}(([+-]\\d{2}(:?\\d{2})?)|Z)$`):new RegExp(`^\\d{4}-\\d{2}-\\d{2}T\\d{2}:\\d{2}:\\d{2}\\.\\d{${s.precision}}Z$`):0===s.precision?s.offset?new RegExp("^\\d{4}-\\d{2}-\\d{2}T\\d{2}:\\d{2}:\\d{2}(([+-]\\d{2}(:?\\d{2})?)|Z)$"):new RegExp("^\\d{4}-\\d{2}-\\d{2}T\\d{2}:\\d{2}:\\d{2}Z$"):s.offset?new RegExp("^\\d{4}-\\d{2}-\\d{2}T\\d{2}:\\d{2}:\\d{2}(\\.\\d+)?(([+-]\\d{2}(:?\\d{2})?)|Z)$"):new RegExp("^\\d{4}-\\d{2}-\\d{2}T\\d{2}:\\d{2}:\\d{2}(\\.\\d+)?Z$")).test(t.data)||(r=this._getOrReturnCtx(t,r),ft(r,{code:ct.invalid_string,validation:"datetime",message:o.message}),e.dirty()):"ip"===o.kind?(n=t.data,("v4"!==(i=o.version)&&i||!Rt.test(n))&&("v6"!==i&&i||!Bt.test(n))&&(r=this._getOrReturnCtx(t,r),ft(r,{validation:"ip",code:ct.invalid_string,message:o.message}),e.dirty())):it.assertNever(o);var n,i,s;return{status:e.value,value:t.data}}_regex(t,e,r){return this.refinement((e=>t.test(e)),{validation:e,code:ct.invalid_string,..._t.errToObj(r)})}_addCheck(t){return new jt({...this._def,checks:[...this._def.checks,t]})}email(t){return this._addCheck({kind:"email",..._t.errToObj(t)})}url(t){return this._addCheck({kind:"url",..._t.errToObj(t)})}emoji(t){return this._addCheck({kind:"emoji",..._t.errToObj(t)})}uuid(t){return this._addCheck({kind:"uuid",..._t.errToObj(t)})}cuid(t){return this._addCheck({kind:"cuid",..._t.errToObj(t)})}cuid2(t){return this._addCheck({kind:"cuid2",..._t.errToObj(t)})}ulid(t){return this._addCheck({kind:"ulid",..._t.errToObj(t)})}ip(t){return this._addCheck({kind:"ip",..._t.errToObj(t)})}datetime(t){var e;return"string"==typeof t?this._addCheck({kind:"datetime",precision:null,offset:!1,message:t}):this._addCheck({kind:"datetime",precision:void 0===(null==t?void 0:t.precision)?null:null==t?void 0:t.precision,offset:null!==(e=null==t?void 0:t.offset)&&void 0!==e&&e,..._t.errToObj(null==t?void 0:t.message)})}regex(t,e){return this._addCheck({kind:"regex",regex:t,..._t.errToObj(e)})}includes(t,e){return this._addCheck({kind:"includes",value:t,position:null==e?void 0:e.position,..._t.errToObj(null==e?void 0:e.message)})}startsWith(t,e){return this._addCheck({kind:"startsWith",value:t,..._t.errToObj(e)})}endsWith(t,e){return this._addCheck({kind:"endsWith",value:t,..._t.errToObj(e)})}min(t,e){return this._addCheck({kind:"min",value:t,..._t.errToObj(e)})}max(t,e){return this._addCheck({kind:"max",value:t,..._t.errToObj(e)})}length(t,e){return this._addCheck({kind:"length",value:t,..._t.errToObj(e)})}nonempty(t){return this.min(1,_t.errToObj(t))}trim(){return new jt({...this._def,checks:[...this._def.checks,{kind:"trim"}]})}toLowerCase(){return new jt({...this._def,checks:[...this._def.checks,{kind:"toLowerCase"}]})}toUpperCase(){return new jt({...this._def,checks:[...this._def.checks,{kind:"toUpperCase"}]})}get isDatetime(){return!!this._def.checks.find((t=>"datetime"===t.kind))}get isEmail(){return!!this._def.checks.find((t=>"email"===t.kind))}get isURL(){return!!this._def.checks.find((t=>"url"===t.kind))}get isEmoji(){return!!this._def.checks.find((t=>"emoji"===t.kind))}get isUUID(){return!!this._def.checks.find((t=>"uuid"===t.kind))}get isCUID(){return!!this._def.checks.find((t=>"cuid"===t.kind))}get isCUID2(){return!!this._def.checks.find((t=>"cuid2"===t.kind))}get isULID(){return!!this._def.checks.find((t=>"ulid"===t.kind))}get isIP(){return!!this._def.checks.find((t=>"ip"===t.kind))}get minLength(){let t=null;for(const e of this._def.checks)"min"===e.kind&&(null===t||e.value>t)&&(t=e.value);return t}get maxLength(){let t=null;for(const e of this._def.checks)"max"===e.kind&&(null===t||e.value<t)&&(t=e.value);return t}}function Lt(t,e){const r=(t.toString().split(".")[1]||"").length,n=(e.toString().split(".")[1]||"").length,i=r>n?r:n;return parseInt(t.toFixed(i).replace(".",""))%parseInt(e.toFixed(i).replace(".",""))/Math.pow(10,i)}jt.create=t=>{var e;return new jt({checks:[],typeName:Se.ZodString,coerce:null!==(e=null==t?void 0:t.coerce)&&void 0!==e&&e,...St(t)})};class Ut extends Ct{constructor(){super(...arguments),this.min=this.gte,this.max=this.lte,this.step=this.multipleOf}_parse(t){if(this._def.coerce&&(t.data=Number(t.data)),this._getType(t)!==ot.number){const e=this._getOrReturnCtx(t);return ft(e,{code:ct.invalid_type,expected:ot.number,received:e.parsedType}),gt}let e;const r=new mt;for(const n of this._def.checks)"int"===n.kind?it.isInteger(t.data)||(e=this._getOrReturnCtx(t,e),ft(e,{code:ct.invalid_type,expected:"integer",received:"float",message:n.message}),r.dirty()):"min"===n.kind?(n.inclusive?t.data<n.value:t.data<=n.value)&&(e=this._getOrReturnCtx(t,e),ft(e,{code:ct.too_small,minimum:n.value,type:"number",inclusive:n.inclusive,exact:!1,message:n.message}),r.dirty()):"max"===n.kind?(n.inclusive?t.data>n.value:t.data>=n.value)&&(e=this._getOrReturnCtx(t,e),ft(e,{code:ct.too_big,maximum:n.value,type:"number",inclusive:n.inclusive,exact:!1,message:n.message}),r.dirty()):"multipleOf"===n.kind?0!==Lt(t.data,n.value)&&(e=this._getOrReturnCtx(t,e),ft(e,{code:ct.not_multiple_of,multipleOf:n.value,message:n.message}),r.dirty()):"finite"===n.kind?Number.isFinite(t.data)||(e=this._getOrReturnCtx(t,e),ft(e,{code:ct.not_finite,message:n.message}),r.dirty()):it.assertNever(n);return{status:r.value,value:t.data}}gte(t,e){return this.setLimit("min",t,!0,_t.toString(e))}gt(t,e){return this.setLimit("min",t,!1,_t.toString(e))}lte(t,e){return this.setLimit("max",t,!0,_t.toString(e))}lt(t,e){return this.setLimit("max",t,!1,_t.toString(e))}setLimit(t,e,r,n){return new Ut({...this._def,checks:[...this._def.checks,{kind:t,value:e,inclusive:r,message:_t.toString(n)}]})}_addCheck(t){return new Ut({...this._def,checks:[...this._def.checks,t]})}int(t){return this._addCheck({kind:"int",message:_t.toString(t)})}positive(t){return this._addCheck({kind:"min",value:0,inclusive:!1,message:_t.toString(t)})}negative(t){return this._addCheck({kind:"max",value:0,inclusive:!1,message:_t.toString(t)})}nonpositive(t){return this._addCheck({kind:"max",value:0,inclusive:!0,message:_t.toString(t)})}nonnegative(t){return this._addCheck({kind:"min",value:0,inclusive:!0,message:_t.toString(t)})}multipleOf(t,e){return this._addCheck({kind:"multipleOf",value:t,message:_t.toString(e)})}finite(t){return this._addCheck({kind:"finite",message:_t.toString(t)})}safe(t){return this._addCheck({kind:"min",inclusive:!0,value:Number.MIN_SAFE_INTEGER,message:_t.toString(t)})._addCheck({kind:"max",inclusive:!0,value:Number.MAX_SAFE_INTEGER,message:_t.toString(t)})}get minValue(){let t=null;for(const e of this._def.checks)"min"===e.kind&&(null===t||e.value>t)&&(t=e.value);return t}get maxValue(){let t=null;for(const e of this._def.checks)"max"===e.kind&&(null===t||e.value<t)&&(t=e.value);return t}get isInt(){return!!this._def.checks.find((t=>"int"===t.kind||"multipleOf"===t.kind&&it.isInteger(t.value)))}get isFinite(){let t=null,e=null;for(const r of this._def.checks){if("finite"===r.kind||"int"===r.kind||"multipleOf"===r.kind)return!0;"min"===r.kind?(null===e||r.value>e)&&(e=r.value):"max"===r.kind&&(null===t||r.value<t)&&(t=r.value)}return Number.isFinite(e)&&Number.isFinite(t)}}Ut.create=t=>new Ut({checks:[],typeName:Se.ZodNumber,coerce:(null==t?void 0:t.coerce)||!1,...St(t)});class Dt extends Ct{constructor(){super(...arguments),this.min=this.gte,this.max=this.lte}_parse(t){if(this._def.coerce&&(t.data=BigInt(t.data)),this._getType(t)!==ot.bigint){const e=this._getOrReturnCtx(t);return ft(e,{code:ct.invalid_type,expected:ot.bigint,received:e.parsedType}),gt}let e;const r=new mt;for(const n of this._def.checks)"min"===n.kind?(n.inclusive?t.data<n.value:t.data<=n.value)&&(e=this._getOrReturnCtx(t,e),ft(e,{code:ct.too_small,type:"bigint",minimum:n.value,inclusive:n.inclusive,message:n.message}),r.dirty()):"max"===n.kind?(n.inclusive?t.data>n.value:t.data>=n.value)&&(e=this._getOrReturnCtx(t,e),ft(e,{code:ct.too_big,type:"bigint",maximum:n.value,inclusive:n.inclusive,message:n.message}),r.dirty()):"multipleOf"===n.kind?t.data%n.value!==BigInt(0)&&(e=this._getOrReturnCtx(t,e),ft(e,{code:ct.not_multiple_of,multipleOf:n.value,message:n.message}),r.dirty()):it.assertNever(n);return{status:r.value,value:t.data}}gte(t,e){return this.setLimit("min",t,!0,_t.toString(e))}gt(t,e){return this.setLimit("min",t,!1,_t.toString(e))}lte(t,e){return this.setLimit("max",t,!0,_t.toString(e))}lt(t,e){return this.setLimit("max",t,!1,_t.toString(e))}setLimit(t,e,r,n){return new Dt({...this._def,checks:[...this._def.checks,{kind:t,value:e,inclusive:r,message:_t.toString(n)}]})}_addCheck(t){return new Dt({...this._def,checks:[...this._def.checks,t]})}positive(t){return this._addCheck({kind:"min",value:BigInt(0),inclusive:!1,message:_t.toString(t)})}negative(t){return this._addCheck({kind:"max",value:BigInt(0),inclusive:!1,message:_t.toString(t)})}nonpositive(t){return this._addCheck({kind:"max",value:BigInt(0),inclusive:!0,message:_t.toString(t)})}nonnegative(t){return this._addCheck({kind:"min",value:BigInt(0),inclusive:!0,message:_t.toString(t)})}multipleOf(t,e){return this._addCheck({kind:"multipleOf",value:t,message:_t.toString(e)})}get minValue(){let t=null;for(const e of this._def.checks)"min"===e.kind&&(null===t||e.value>t)&&(t=e.value);return t}get maxValue(){let t=null;for(const e of this._def.checks)"max"===e.kind&&(null===t||e.value<t)&&(t=e.value);return t}}Dt.create=t=>{var e;return new Dt({checks:[],typeName:Se.ZodBigInt,coerce:null!==(e=null==t?void 0:t.coerce)&&void 0!==e&&e,...St(t)})};class $t extends Ct{_parse(t){if(this._def.coerce&&(t.data=Boolean(t.data)),this._getType(t)!==ot.boolean){const e=this._getOrReturnCtx(t);return ft(e,{code:ct.invalid_type,expected:ot.boolean,received:e.parsedType}),gt}return wt(t.data)}}$t.create=t=>new $t({typeName:Se.ZodBoolean,coerce:(null==t?void 0:t.coerce)||!1,...St(t)});class Ft extends Ct{_parse(t){if(this._def.coerce&&(t.data=new Date(t.data)),this._getType(t)!==ot.date){const e=this._getOrReturnCtx(t);return ft(e,{code:ct.invalid_type,expected:ot.date,received:e.parsedType}),gt}if(isNaN(t.data.getTime()))return ft(this._getOrReturnCtx(t),{code:ct.invalid_date}),gt;const e=new mt;let r;for(const n of this._def.checks)"min"===n.kind?t.data.getTime()<n.value&&(r=this._getOrReturnCtx(t,r),ft(r,{code:ct.too_small,message:n.message,inclusive:!0,exact:!1,minimum:n.value,type:"date"}),e.dirty()):"max"===n.kind?t.data.getTime()>n.value&&(r=this._getOrReturnCtx(t,r),ft(r,{code:ct.too_big,message:n.message,inclusive:!0,exact:!1,maximum:n.value,type:"date"}),e.dirty()):it.assertNever(n);return{status:e.value,value:new Date(t.data.getTime())}}_addCheck(t){return new Ft({...this._def,checks:[...this._def.checks,t]})}min(t,e){return this._addCheck({kind:"min",value:t.getTime(),message:_t.toString(e)})}max(t,e){return this._addCheck({kind:"max",value:t.getTime(),message:_t.toString(e)})}get minDate(){let t=null;for(const e of this._def.checks)"min"===e.kind&&(null===t||e.value>t)&&(t=e.value);return null!=t?new Date(t):null}get maxDate(){let t=null;for(const e of this._def.checks)"max"===e.kind&&(null===t||e.value<t)&&(t=e.value);return null!=t?new Date(t):null}}Ft.create=t=>new Ft({checks:[],coerce:(null==t?void 0:t.coerce)||!1,typeName:Se.ZodDate,...St(t)});class zt extends Ct{_parse(t){if(this._getType(t)!==ot.symbol){const e=this._getOrReturnCtx(t);return ft(e,{code:ct.invalid_type,expected:ot.symbol,received:e.parsedType}),gt}return wt(t.data)}}zt.create=t=>new zt({typeName:Se.ZodSymbol,...St(t)});class Ht extends Ct{_parse(t){if(this._getType(t)!==ot.undefined){const e=this._getOrReturnCtx(t);return ft(e,{code:ct.invalid_type,expected:ot.undefined,received:e.parsedType}),gt}return wt(t.data)}}Ht.create=t=>new Ht({typeName:Se.ZodUndefined,...St(t)});class Wt extends Ct{_parse(t){if(this._getType(t)!==ot.null){const e=this._getOrReturnCtx(t);return ft(e,{code:ct.invalid_type,expected:ot.null,received:e.parsedType}),gt}return wt(t.data)}}Wt.create=t=>new Wt({typeName:Se.ZodNull,...St(t)});class qt extends Ct{constructor(){super(...arguments),this._any=!0}_parse(t){return wt(t.data)}}qt.create=t=>new qt({typeName:Se.ZodAny,...St(t)});class Vt extends Ct{constructor(){super(...arguments),this._unknown=!0}_parse(t){return wt(t.data)}}Vt.create=t=>new Vt({typeName:Se.ZodUnknown,...St(t)});class Gt extends Ct{_parse(t){const e=this._getOrReturnCtx(t);return ft(e,{code:ct.invalid_type,expected:ot.never,received:e.parsedType}),gt}}Gt.create=t=>new Gt({typeName:Se.ZodNever,...St(t)});class Kt extends Ct{_parse(t){if(this._getType(t)!==ot.undefined){const e=this._getOrReturnCtx(t);return ft(e,{code:ct.invalid_type,expected:ot.void,received:e.parsedType}),gt}return wt(t.data)}}Kt.create=t=>new Kt({typeName:Se.ZodVoid,...St(t)});class Zt extends Ct{_parse(t){const{ctx:e,status:r}=this._processInputParams(t),n=this._def;if(e.parsedType!==ot.array)return ft(e,{code:ct.invalid_type,expected:ot.array,received:e.parsedType}),gt;if(null!==n.exactLength){const t=e.data.length>n.exactLength.value,i=e.data.length<n.exactLength.value;(t||i)&&(ft(e,{code:t?ct.too_big:ct.too_small,minimum:i?n.exactLength.value:void 0,maximum:t?n.exactLength.value:void 0,type:"array",inclusive:!0,exact:!0,message:n.exactLength.message}),r.dirty())}if(null!==n.minLength&&e.data.length<n.minLength.value&&(ft(e,{code:ct.too_small,minimum:n.minLength.value,type:"array",inclusive:!0,exact:!1,message:n.minLength.message}),r.dirty()),null!==n.maxLength&&e.data.length>n.maxLength.value&&(ft(e,{code:ct.too_big,maximum:n.maxLength.value,type:"array",inclusive:!0,exact:!1,message:n.maxLength.message}),r.dirty()),e.common.async)return Promise.all([...e.data].map(((t,r)=>n.type._parseAsync(new At(e,t,e.path,r))))).then((t=>mt.mergeArray(r,t)));const i=[...e.data].map(((t,r)=>n.type._parseSync(new At(e,t,e.path,r))));return mt.mergeArray(r,i)}get element(){return this._def.type}min(t,e){return new Zt({...this._def,minLength:{value:t,message:_t.toString(e)}})}max(t,e){return new Zt({...this._def,maxLength:{value:t,message:_t.toString(e)}})}length(t,e){return new Zt({...this._def,exactLength:{value:t,message:_t.toString(e)}})}nonempty(t){return this.min(1,t)}}function Jt(t){if(t instanceof Qt){const e={};for(const r in t.shape){const n=t.shape[r];e[r]=me.create(Jt(n))}return new Qt({...t._def,shape:()=>e})}return t instanceof Zt?new Zt({...t._def,type:Jt(t.element)}):t instanceof me?me.create(Jt(t.unwrap())):t instanceof ge?ge.create(Jt(t.unwrap())):t instanceof ne?ne.create(t.items.map((t=>Jt(t)))):t}Zt.create=(t,e)=>new Zt({type:t,minLength:null,maxLength:null,exactLength:null,typeName:Se.ZodArray,...St(e)});class Qt extends Ct{constructor(){super(...arguments),this._cached=null,this.nonstrict=this.passthrough,this.augment=this.extend}_getCached(){if(null!==this._cached)return this._cached;const t=this._def.shape(),e=it.objectKeys(t);return this._cached={shape:t,keys:e}}_parse(t){if(this._getType(t)!==ot.object){const e=this._getOrReturnCtx(t);return ft(e,{code:ct.invalid_type,expected:ot.object,received:e.parsedType}),gt}const{status:e,ctx:r}=this._processInputParams(t),{shape:n,keys:i}=this._getCached(),s=[];if(!(this._def.catchall instanceof Gt&&"strip"===this._def.unknownKeys))for(const t in r.data)i.includes(t)||s.push(t);const o=[];for(const t of i){const e=n[t],i=r.data[t];o.push({key:{status:"valid",value:t},value:e._parse(new At(r,i,r.path,t)),alwaysSet:t in r.data})}if(this._def.catchall instanceof Gt){const t=this._def.unknownKeys;if("passthrough"===t)for(const t of s)o.push({key:{status:"valid",value:t},value:{status:"valid",value:r.data[t]}});else if("strict"===t)s.length>0&&(ft(r,{code:ct.unrecognized_keys,keys:s}),e.dirty());else if("strip"!==t)throw new Error("Internal ZodObject error: invalid unknownKeys value.")}else{const t=this._def.catchall;for(const e of s){const n=r.data[e];o.push({key:{status:"valid",value:e},value:t._parse(new At(r,n,r.path,e)),alwaysSet:e in r.data})}}return r.common.async?Promise.resolve().then((async()=>{const t=[];for(const e of o){const r=await e.key;t.push({key:r,value:await e.value,alwaysSet:e.alwaysSet})}return t})).then((t=>mt.mergeObjectSync(e,t))):mt.mergeObjectSync(e,o)}get shape(){return this._def.shape()}strict(t){return _t.errToObj,new Qt({...this._def,unknownKeys:"strict",...void 0!==t?{errorMap:(e,r)=>{var n,i,s,o;const a=null!==(s=null===(i=(n=this._def).errorMap)||void 0===i?void 0:i.call(n,e,r).message)&&void 0!==s?s:r.defaultError;return"unrecognized_keys"===e.code?{message:null!==(o=_t.errToObj(t).message)&&void 0!==o?o:a}:{message:a}}}:{}})}strip(){return new Qt({...this._def,unknownKeys:"strip"})}passthrough(){return new Qt({...this._def,unknownKeys:"passthrough"})}extend(t){return new Qt({...this._def,shape:()=>({...this._def.shape(),...t})})}merge(t){return new Qt({unknownKeys:t._def.unknownKeys,catchall:t._def.catchall,shape:()=>({...this._def.shape(),...t._def.shape()}),typeName:Se.ZodObject})}setKey(t,e){return this.augment({[t]:e})}catchall(t){return new Qt({...this._def,catchall:t})}pick(t){const e={};return it.objectKeys(t).forEach((r=>{t[r]&&this.shape[r]&&(e[r]=this.shape[r])})),new Qt({...this._def,shape:()=>e})}omit(t){const e={};return it.objectKeys(this.shape).forEach((r=>{t[r]||(e[r]=this.shape[r])})),new Qt({...this._def,shape:()=>e})}deepPartial(){return Jt(this)}partial(t){const e={};return it.objectKeys(this.shape).forEach((r=>{const n=this.shape[r];t&&!t[r]?e[r]=n:e[r]=n.optional()})),new Qt({...this._def,shape:()=>e})}required(t){const e={};return it.objectKeys(this.shape).forEach((r=>{if(t&&!t[r])e[r]=this.shape[r];else{let t=this.shape[r];for(;t instanceof me;)t=t._def.innerType;e[r]=t}})),new Qt({...this._def,shape:()=>e})}keyof(){return ue(it.objectKeys(this.shape))}}Qt.create=(t,e)=>new Qt({shape:()=>t,unknownKeys:"strip",catchall:Gt.create(),typeName:Se.ZodObject,...St(e)}),Qt.strictCreate=(t,e)=>new Qt({shape:()=>t,unknownKeys:"strict",catchall:Gt.create(),typeName:Se.ZodObject,...St(e)}),Qt.lazycreate=(t,e)=>new Qt({shape:t,unknownKeys:"strip",catchall:Gt.create(),typeName:Se.ZodObject,...St(e)});class Yt extends Ct{_parse(t){const{ctx:e}=this._processInputParams(t),r=this._def.options;if(e.common.async)return Promise.all(r.map((async t=>{const r={...e,common:{...e.common,issues:[]},parent:null};return{result:await t._parseAsync({data:e.data,path:e.path,parent:r}),ctx:r}}))).then((function(t){for(const e of t)if("valid"===e.result.status)return e.result;for(const r of t)if("dirty"===r.result.status)return e.common.issues.push(...r.ctx.common.issues),r.result;const r=t.map((t=>new lt(t.ctx.common.issues)));return ft(e,{code:ct.invalid_union,unionErrors:r}),gt}));{let t;const n=[];for(const i of r){const r={...e,common:{...e.common,issues:[]},parent:null},s=i._parseSync({data:e.data,path:e.path,parent:r});if("valid"===s.status)return s;"dirty"!==s.status||t||(t={result:s,ctx:r}),r.common.issues.length&&n.push(r.common.issues)}if(t)return e.common.issues.push(...t.ctx.common.issues),t.result;const i=n.map((t=>new lt(t)));return ft(e,{code:ct.invalid_union,unionErrors:i}),gt}}get options(){return this._def.options}}Yt.create=(t,e)=>new Yt({options:t,typeName:Se.ZodUnion,...St(e)});const Xt=t=>t instanceof ce?Xt(t.schema):t instanceof fe?Xt(t.innerType()):t instanceof le?[t.value]:t instanceof he?t.options:t instanceof de?Object.keys(t.enum):t instanceof ye?Xt(t._def.innerType):t instanceof Ht?[void 0]:t instanceof Wt?[null]:null;class te extends Ct{_parse(t){const{ctx:e}=this._processInputParams(t);if(e.parsedType!==ot.object)return ft(e,{code:ct.invalid_type,expected:ot.object,received:e.parsedType}),gt;const r=this.discriminator,n=e.data[r],i=this.optionsMap.get(n);return i?e.common.async?i._parseAsync({data:e.data,path:e.path,parent:e}):i._parseSync({data:e.data,path:e.path,parent:e}):(ft(e,{code:ct.invalid_union_discriminator,options:Array.from(this.optionsMap.keys()),path:[r]}),gt)}get discriminator(){return this._def.discriminator}get options(){return this._def.options}get optionsMap(){return this._def.optionsMap}static create(t,e,r){const n=new Map;for(const r of e){const e=Xt(r.shape[t]);if(!e)throw new Error(`A discriminator value for key \`${t}\` could not be extracted from all schema options`);for(const i of e){if(n.has(i))throw new Error(`Discriminator property ${String(t)} has duplicate value ${String(i)}`);n.set(i,r)}}return new te({typeName:Se.ZodDiscriminatedUnion,discriminator:t,options:e,optionsMap:n,...St(r)})}}function ee(t,e){const r=at(t),n=at(e);if(t===e)return{valid:!0,data:t};if(r===ot.object&&n===ot.object){const r=it.objectKeys(e),n=it.objectKeys(t).filter((t=>-1!==r.indexOf(t))),i={...t,...e};for(const r of n){const n=ee(t[r],e[r]);if(!n.valid)return{valid:!1};i[r]=n.data}return{valid:!0,data:i}}if(r===ot.array&&n===ot.array){if(t.length!==e.length)return{valid:!1};const r=[];for(let n=0;n<t.length;n++){const i=ee(t[n],e[n]);if(!i.valid)return{valid:!1};r.push(i.data)}return{valid:!0,data:r}}return r===ot.date&&n===ot.date&&+t==+e?{valid:!0,data:t}:{valid:!1}}class re extends Ct{_parse(t){const{status:e,ctx:r}=this._processInputParams(t),n=(t,n)=>{if(bt(t)||bt(n))return gt;const i=ee(t.value,n.value);return i.valid?((vt(t)||vt(n))&&e.dirty(),{status:e.value,value:i.data}):(ft(r,{code:ct.invalid_intersection_types}),gt)};return r.common.async?Promise.all([this._def.left._parseAsync({data:r.data,path:r.path,parent:r}),this._def.right._parseAsync({data:r.data,path:r.path,parent:r})]).then((([t,e])=>n(t,e))):n(this._def.left._parseSync({data:r.data,path:r.path,parent:r}),this._def.right._parseSync({data:r.data,path:r.path,parent:r}))}}re.create=(t,e,r)=>new re({left:t,right:e,typeName:Se.ZodIntersection,...St(r)});class ne extends Ct{_parse(t){const{status:e,ctx:r}=this._processInputParams(t);if(r.parsedType!==ot.array)return ft(r,{code:ct.invalid_type,expected:ot.array,received:r.parsedType}),gt;if(r.data.length<this._def.items.length)return ft(r,{code:ct.too_small,minimum:this._def.items.length,inclusive:!0,exact:!1,type:"array"}),gt;!this._def.rest&&r.data.length>this._def.items.length&&(ft(r,{code:ct.too_big,maximum:this._def.items.length,inclusive:!0,exact:!1,type:"array"}),e.dirty());const n=[...r.data].map(((t,e)=>{const n=this._def.items[e]||this._def.rest;return n?n._parse(new At(r,t,r.path,e)):null})).filter((t=>!!t));return r.common.async?Promise.all(n).then((t=>mt.mergeArray(e,t))):mt.mergeArray(e,n)}get items(){return this._def.items}rest(t){return new ne({...this._def,rest:t})}}ne.create=(t,e)=>{if(!Array.isArray(t))throw new Error("You must pass an array of schemas to z.tuple([ ... ])");return new ne({items:t,typeName:Se.ZodTuple,rest:null,...St(e)})};class ie extends Ct{get keySchema(){return this._def.keyType}get valueSchema(){return this._def.valueType}_parse(t){const{status:e,ctx:r}=this._processInputParams(t);if(r.parsedType!==ot.object)return ft(r,{code:ct.invalid_type,expected:ot.object,received:r.parsedType}),gt;const n=[],i=this._def.keyType,s=this._def.valueType;for(const t in r.data)n.push({key:i._parse(new At(r,t,r.path,t)),value:s._parse(new At(r,r.data[t],r.path,t))});return r.common.async?mt.mergeObjectAsync(e,n):mt.mergeObjectSync(e,n)}get element(){return this._def.valueType}static create(t,e,r){return new ie(e instanceof Ct?{keyType:t,valueType:e,typeName:Se.ZodRecord,...St(r)}:{keyType:jt.create(),valueType:t,typeName:Se.ZodRecord,...St(e)})}}class se extends Ct{get keySchema(){return this._def.keyType}get valueSchema(){return this._def.valueType}_parse(t){const{status:e,ctx:r}=this._processInputParams(t);if(r.parsedType!==ot.map)return ft(r,{code:ct.invalid_type,expected:ot.map,received:r.parsedType}),gt;const n=this._def.keyType,i=this._def.valueType,s=[...r.data.entries()].map((([t,e],s)=>({key:n._parse(new At(r,t,r.path,[s,"key"])),value:i._parse(new At(r,e,r.path,[s,"value"]))})));if(r.common.async){const t=new Map;return Promise.resolve().then((async()=>{for(const r of s){const n=await r.key,i=await r.value;if("aborted"===n.status||"aborted"===i.status)return gt;"dirty"!==n.status&&"dirty"!==i.status||e.dirty(),t.set(n.value,i.value)}return{status:e.value,value:t}}))}{const t=new Map;for(const r of s){const n=r.key,i=r.value;if("aborted"===n.status||"aborted"===i.status)return gt;"dirty"!==n.status&&"dirty"!==i.status||e.dirty(),t.set(n.value,i.value)}return{status:e.value,value:t}}}}se.create=(t,e,r)=>new se({valueType:e,keyType:t,typeName:Se.ZodMap,...St(r)});class oe extends Ct{_parse(t){const{status:e,ctx:r}=this._processInputParams(t);if(r.parsedType!==ot.set)return ft(r,{code:ct.invalid_type,expected:ot.set,received:r.parsedType}),gt;const n=this._def;null!==n.minSize&&r.data.size<n.minSize.value&&(ft(r,{code:ct.too_small,minimum:n.minSize.value,type:"set",inclusive:!0,exact:!1,message:n.minSize.message}),e.dirty()),null!==n.maxSize&&r.data.size>n.maxSize.value&&(ft(r,{code:ct.too_big,maximum:n.maxSize.value,type:"set",inclusive:!0,exact:!1,message:n.maxSize.message}),e.dirty());const i=this._def.valueType;function s(t){const r=new Set;for(const n of t){if("aborted"===n.status)return gt;"dirty"===n.status&&e.dirty(),r.add(n.value)}return{status:e.value,value:r}}const o=[...r.data.values()].map(((t,e)=>i._parse(new At(r,t,r.path,e))));return r.common.async?Promise.all(o).then((t=>s(t))):s(o)}min(t,e){return new oe({...this._def,minSize:{value:t,message:_t.toString(e)}})}max(t,e){return new oe({...this._def,maxSize:{value:t,message:_t.toString(e)}})}size(t,e){return this.min(t,e).max(t,e)}nonempty(t){return this.min(1,t)}}oe.create=(t,e)=>new oe({valueType:t,minSize:null,maxSize:null,typeName:Se.ZodSet,...St(e)});class ae extends Ct{constructor(){super(...arguments),this.validate=this.implement}_parse(t){const{ctx:e}=this._processInputParams(t);if(e.parsedType!==ot.function)return ft(e,{code:ct.invalid_type,expected:ot.function,received:e.parsedType}),gt;function r(t,r){return pt({data:t,path:e.path,errorMaps:[e.common.contextualErrorMap,e.schemaErrorMap,dt(),ut].filter((t=>!!t)),issueData:{code:ct.invalid_arguments,argumentsError:r}})}function n(t,r){return pt({data:t,path:e.path,errorMaps:[e.common.contextualErrorMap,e.schemaErrorMap,dt(),ut].filter((t=>!!t)),issueData:{code:ct.invalid_return_type,returnTypeError:r}})}const i={errorMap:e.common.contextualErrorMap},s=e.data;if(this._def.returns instanceof pe){const t=this;return wt((async function(...e){const o=new lt([]),a=await t._def.args.parseAsync(e,i).catch((t=>{throw o.addIssue(r(e,t)),o})),c=await Reflect.apply(s,this,a);return await t._def.returns._def.type.parseAsync(c,i).catch((t=>{throw o.addIssue(n(c,t)),o}))}))}{const t=this;return wt((function(...e){const o=t._def.args.safeParse(e,i);if(!o.success)throw new lt([r(e,o.error)]);const a=Reflect.apply(s,this,o.data),c=t._def.returns.safeParse(a,i);if(!c.success)throw new lt([n(a,c.error)]);return c.data}))}}parameters(){return this._def.args}returnType(){return this._def.returns}args(...t){return new ae({...this._def,args:ne.create(t).rest(Vt.create())})}returns(t){return new ae({...this._def,returns:t})}implement(t){return this.parse(t)}strictImplement(t){return this.parse(t)}static create(t,e,r){return new ae({args:t||ne.create([]).rest(Vt.create()),returns:e||Vt.create(),typeName:Se.ZodFunction,...St(r)})}}class ce extends Ct{get schema(){return this._def.getter()}_parse(t){const{ctx:e}=this._processInputParams(t);return this._def.getter()._parse({data:e.data,path:e.path,parent:e})}}ce.create=(t,e)=>new ce({getter:t,typeName:Se.ZodLazy,...St(e)});class le extends Ct{_parse(t){if(t.data!==this._def.value){const e=this._getOrReturnCtx(t);return ft(e,{received:e.data,code:ct.invalid_literal,expected:this._def.value}),gt}return{status:"valid",value:t.data}}get value(){return this._def.value}}function ue(t,e){return new he({values:t,typeName:Se.ZodEnum,...St(e)})}le.create=(t,e)=>new le({value:t,typeName:Se.ZodLiteral,...St(e)});class he extends Ct{_parse(t){if("string"!=typeof t.data){const e=this._getOrReturnCtx(t),r=this._def.values;return ft(e,{expected:it.joinValues(r),received:e.parsedType,code:ct.invalid_type}),gt}if(-1===this._def.values.indexOf(t.data)){const e=this._getOrReturnCtx(t),r=this._def.values;return ft(e,{received:e.data,code:ct.invalid_enum_value,options:r}),gt}return wt(t.data)}get options(){return this._def.values}get enum(){const t={};for(const e of this._def.values)t[e]=e;return t}get Values(){const t={};for(const e of this._def.values)t[e]=e;return t}get Enum(){const t={};for(const e of this._def.values)t[e]=e;return t}extract(t){return he.create(t)}exclude(t){return he.create(this.options.filter((e=>!t.includes(e))))}}he.create=ue;class de extends Ct{_parse(t){const e=it.getValidEnumValues(this._def.values),r=this._getOrReturnCtx(t);if(r.parsedType!==ot.string&&r.parsedType!==ot.number){const t=it.objectValues(e);return ft(r,{expected:it.joinValues(t),received:r.parsedType,code:ct.invalid_type}),gt}if(-1===e.indexOf(t.data)){const t=it.objectValues(e);return ft(r,{received:r.data,code:ct.invalid_enum_value,options:t}),gt}return wt(t.data)}get enum(){return this._def.values}}de.create=(t,e)=>new de({values:t,typeName:Se.ZodNativeEnum,...St(e)});class pe extends Ct{unwrap(){return this._def.type}_parse(t){const{ctx:e}=this._processInputParams(t);if(e.parsedType!==ot.promise&&!1===e.common.async)return ft(e,{code:ct.invalid_type,expected:ot.promise,received:e.parsedType}),gt;const r=e.parsedType===ot.promise?e.data:Promise.resolve(e.data);return wt(r.then((t=>this._def.type.parseAsync(t,{path:e.path,errorMap:e.common.contextualErrorMap}))))}}pe.create=(t,e)=>new pe({type:t,typeName:Se.ZodPromise,...St(e)});class fe extends Ct{innerType(){return this._def.schema}sourceType(){return this._def.schema._def.typeName===Se.ZodEffects?this._def.schema.sourceType():this._def.schema}_parse(t){const{status:e,ctx:r}=this._processInputParams(t),n=this._def.effect||null,i={addIssue:t=>{ft(r,t),t.fatal?e.abort():e.dirty()},get path(){return r.path}};if(i.addIssue=i.addIssue.bind(i),"preprocess"===n.type){const t=n.transform(r.data,i);return r.common.issues.length?{status:"dirty",value:r.data}:r.common.async?Promise.resolve(t).then((t=>this._def.schema._parseAsync({data:t,path:r.path,parent:r}))):this._def.schema._parseSync({data:t,path:r.path,parent:r})}if("refinement"===n.type){const t=t=>{const e=n.refinement(t,i);if(r.common.async)return Promise.resolve(e);if(e instanceof Promise)throw new Error("Async refinement encountered during synchronous parse operation. Use .parseAsync instead.");return t};if(!1===r.common.async){const n=this._def.schema._parseSync({data:r.data,path:r.path,parent:r});return"aborted"===n.status?gt:("dirty"===n.status&&e.dirty(),t(n.value),{status:e.value,value:n.value})}return this._def.schema._parseAsync({data:r.data,path:r.path,parent:r}).then((r=>"aborted"===r.status?gt:("dirty"===r.status&&e.dirty(),t(r.value).then((()=>({status:e.value,value:r.value}))))))}if("transform"===n.type){if(!1===r.common.async){const t=this._def.schema._parseSync({data:r.data,path:r.path,parent:r});if(!Et(t))return t;const s=n.transform(t.value,i);if(s instanceof Promise)throw new Error("Asynchronous transform encountered during synchronous parse operation. Use .parseAsync instead.");return{status:e.value,value:s}}return this._def.schema._parseAsync({data:r.data,path:r.path,parent:r}).then((t=>Et(t)?Promise.resolve(n.transform(t.value,i)).then((t=>({status:e.value,value:t}))):t))}it.assertNever(n)}}fe.create=(t,e,r)=>new fe({schema:t,typeName:Se.ZodEffects,effect:e,...St(r)}),fe.createWithPreprocess=(t,e,r)=>new fe({schema:e,effect:{type:"preprocess",transform:t},typeName:Se.ZodEffects,...St(r)});class me extends Ct{_parse(t){return this._getType(t)===ot.undefined?wt(void 0):this._def.innerType._parse(t)}unwrap(){return this._def.innerType}}me.create=(t,e)=>new me({innerType:t,typeName:Se.ZodOptional,...St(e)});class ge extends Ct{_parse(t){return this._getType(t)===ot.null?wt(null):this._def.innerType._parse(t)}unwrap(){return this._def.innerType}}ge.create=(t,e)=>new ge({innerType:t,typeName:Se.ZodNullable,...St(e)});class ye extends Ct{_parse(t){const{ctx:e}=this._processInputParams(t);let r=e.data;return e.parsedType===ot.undefined&&(r=this._def.defaultValue()),this._def.innerType._parse({data:r,path:e.path,parent:e})}removeDefault(){return this._def.innerType}}ye.create=(t,e)=>new ye({innerType:t,typeName:Se.ZodDefault,defaultValue:"function"==typeof e.default?e.default:()=>e.default,...St(e)});class we extends Ct{_parse(t){const{ctx:e}=this._processInputParams(t),r={...e,common:{...e.common,issues:[]}},n=this._def.innerType._parse({data:r.data,path:r.path,parent:{...r}});return xt(n)?n.then((t=>({status:"valid",value:"valid"===t.status?t.value:this._def.catchValue({get error(){return new lt(r.common.issues)},input:r.data})}))):{status:"valid",value:"valid"===n.status?n.value:this._def.catchValue({get error(){return new lt(r.common.issues)},input:r.data})}}removeCatch(){return this._def.innerType}}we.create=(t,e)=>new we({innerType:t,typeName:Se.ZodCatch,catchValue:"function"==typeof e.catch?e.catch:()=>e.catch,...St(e)});class be extends Ct{_parse(t){if(this._getType(t)!==ot.nan){const e=this._getOrReturnCtx(t);return ft(e,{code:ct.invalid_type,expected:ot.nan,received:e.parsedType}),gt}return{status:"valid",value:t.data}}}be.create=t=>new be({typeName:Se.ZodNaN,...St(t)});const ve=Symbol("zod_brand");class Ee extends Ct{_parse(t){const{ctx:e}=this._processInputParams(t),r=e.data;return this._def.type._parse({data:r,path:e.path,parent:e})}unwrap(){return this._def.type}}class xe extends Ct{_parse(t){const{status:e,ctx:r}=this._processInputParams(t);if(r.common.async)return(async()=>{const t=await this._def.in._parseAsync({data:r.data,path:r.path,parent:r});return"aborted"===t.status?gt:"dirty"===t.status?(e.dirty(),yt(t.value)):this._def.out._parseAsync({data:t.value,path:r.path,parent:r})})();{const t=this._def.in._parseSync({data:r.data,path:r.path,parent:r});return"aborted"===t.status?gt:"dirty"===t.status?(e.dirty(),{status:"dirty",value:t.value}):this._def.out._parseSync({data:t.value,path:r.path,parent:r})}}static create(t,e){return new xe({in:t,out:e,typeName:Se.ZodPipeline})}}class _e extends Ct{_parse(t){const e=this._def.innerType._parse(t);return Et(e)&&(e.value=Object.freeze(e.value)),e}}_e.create=(t,e)=>new _e({innerType:t,typeName:Se.ZodReadonly,...St(e)});const Ae=(t,e={},r)=>t?qt.create().superRefine(((n,i)=>{var s,o;if(!t(n)){const t="function"==typeof e?e(n):"string"==typeof e?{message:e}:e,a=null===(o=null!==(s=t.fatal)&&void 0!==s?s:r)||void 0===o||o,c="string"==typeof t?{message:t}:t;i.addIssue({code:"custom",...c,fatal:a})}})):qt.create(),ke={object:Qt.lazycreate};var Se;!function(t){t.ZodString="ZodString",t.ZodNumber="ZodNumber",t.ZodNaN="ZodNaN",t.ZodBigInt="ZodBigInt",t.ZodBoolean="ZodBoolean",t.ZodDate="ZodDate",t.ZodSymbol="ZodSymbol",t.ZodUndefined="ZodUndefined",t.ZodNull="ZodNull",t.ZodAny="ZodAny",t.ZodUnknown="ZodUnknown",t.ZodNever="ZodNever",t.ZodVoid="ZodVoid",t.ZodArray="ZodArray",t.ZodObject="ZodObject",t.ZodUnion="ZodUnion",t.ZodDiscriminatedUnion="ZodDiscriminatedUnion",t.ZodIntersection="ZodIntersection",t.ZodTuple="ZodTuple",t.ZodRecord="ZodRecord",t.ZodMap="ZodMap",t.ZodSet="ZodSet",t.ZodFunction="ZodFunction",t.ZodLazy="ZodLazy",t.ZodLiteral="ZodLiteral",t.ZodEnum="ZodEnum",t.ZodEffects="ZodEffects",t.ZodNativeEnum="ZodNativeEnum",t.ZodOptional="ZodOptional",t.ZodNullable="ZodNullable",t.ZodDefault="ZodDefault",t.ZodCatch="ZodCatch",t.ZodPromise="ZodPromise",t.ZodBranded="ZodBranded",t.ZodPipeline="ZodPipeline",t.ZodReadonly="ZodReadonly"}(Se||(Se={}));const Ce=jt.create,Me=Ut.create,Ie=be.create,Pe=Dt.create,Oe=$t.create,Te=Ft.create,Ne=zt.create,Re=Ht.create,Be=Wt.create,je=qt.create,Le=Vt.create,Ue=Gt.create,De=Kt.create,$e=Zt.create,Fe=Qt.create,ze=Qt.strictCreate,He=Yt.create,We=te.create,qe=re.create,Ve=ne.create,Ge=ie.create,Ke=se.create,Ze=oe.create,Je=ae.create,Qe=ce.create,Ye=le.create,Xe=he.create,tr=de.create,er=pe.create,rr=fe.create,nr=me.create,ir=ge.create,sr=fe.createWithPreprocess,or=xe.create,ar={string:t=>jt.create({...t,coerce:!0}),number:t=>Ut.create({...t,coerce:!0}),boolean:t=>$t.create({...t,coerce:!0}),bigint:t=>Dt.create({...t,coerce:!0}),date:t=>Ft.create({...t,coerce:!0})},cr=gt;var lr=Object.freeze({__proto__:null,defaultErrorMap:ut,setErrorMap:function(t){ht=t},getErrorMap:dt,makeIssue:pt,EMPTY_PATH:[],addIssueToContext:ft,ParseStatus:mt,INVALID:gt,DIRTY:yt,OK:wt,isAborted:bt,isDirty:vt,isValid:Et,isAsync:xt,get util(){return it},get objectUtil(){return st},ZodParsedType:ot,getParsedType:at,ZodType:Ct,ZodString:jt,ZodNumber:Ut,ZodBigInt:Dt,ZodBoolean:$t,ZodDate:Ft,ZodSymbol:zt,ZodUndefined:Ht,ZodNull:Wt,ZodAny:qt,ZodUnknown:Vt,ZodNever:Gt,ZodVoid:Kt,ZodArray:Zt,ZodObject:Qt,ZodUnion:Yt,ZodDiscriminatedUnion:te,ZodIntersection:re,ZodTuple:ne,ZodRecord:ie,ZodMap:se,ZodSet:oe,ZodFunction:ae,ZodLazy:ce,ZodLiteral:le,ZodEnum:he,ZodNativeEnum:de,ZodPromise:pe,ZodEffects:fe,ZodTransformer:fe,ZodOptional:me,ZodNullable:ge,ZodDefault:ye,ZodCatch:we,ZodNaN:be,BRAND:ve,ZodBranded:Ee,ZodPipeline:xe,ZodReadonly:_e,custom:Ae,Schema:Ct,ZodSchema:Ct,late:ke,get ZodFirstPartyTypeKind(){return Se},coerce:ar,any:je,array:$e,bigint:Pe,boolean:Oe,date:Te,discriminatedUnion:We,effect:rr,enum:Xe,function:Je,instanceof:(t,e={message:`Input not instance of ${t.name}`})=>Ae((e=>e instanceof t),e),intersection:qe,lazy:Qe,literal:Ye,map:Ke,nan:Ie,nativeEnum:tr,never:Ue,null:Be,nullable:ir,number:Me,object:Fe,oboolean:()=>Oe().optional(),onumber:()=>Me().optional(),optional:nr,ostring:()=>Ce().optional(),pipeline:or,preprocess:sr,promise:er,record:Ge,set:Ze,strictObject:ze,string:Ce,symbol:Ne,transformer:rr,tuple:Ve,undefined:Re,union:He,unknown:Le,void:De,NEVER:cr,ZodIssueCode:ct,quotelessJson:t=>JSON.stringify(t,null,2).replace(/"([^"]+)":/g,"$1:"),ZodError:lt});const ur=lr.object({message:lr.string()});function hr(t){return lr.literal(nt[t])}const dr=lr.object({chainId:lr.number()}),pr=lr.object({email:lr.string().email()}),fr=lr.object({otp:lr.string()}),mr=lr.object({chainId:lr.optional(lr.number())}),gr=lr.object({email:lr.string().email()}),yr=lr.object({themeMode:lr.optional(lr.enum(["light","dark"])),themeVariables:lr.optional(lr.record(lr.string(),lr.string().or(lr.number())))}),wr=lr.object({action:lr.enum(["VERIFY_DEVICE","VERIFY_OTP"])}),br=lr.object({email:lr.string().email(),address:lr.string(),chainId:lr.number()}),vr=lr.object({isConnected:lr.boolean()}),Er=lr.object({chainId:lr.number()}),xr=lr.object({email:lr.string().email()}),_r=lr.string(),Ar=lr.object({method:lr.literal("personal_sign"),params:lr.array(lr.any())}),kr=lr.object({method:lr.literal("eth_sendTransaction"),params:lr.array(lr.any())}),Sr=lr.object({method:lr.literal("eth_accounts")}),Cr=lr.object({method:lr.literal("eth_getBalance"),params:lr.array(lr.any())}),Mr=lr.object({method:lr.literal("eth_estimateGas"),params:lr.array(lr.any())}),Ir=lr.object({method:lr.literal("eth_gasPrice")}),Pr=lr.object({method:lr.literal("eth_signTypedData_v4"),params:lr.array(lr.any())}),Or=lr.object({token:lr.string()}),Tr={appEvent:lr.object({type:hr("APP_SWITCH_NETWORK"),payload:dr}).or(lr.object({type:hr("APP_CONNECT_EMAIL"),payload:pr})).or(lr.object({type:hr("APP_CONNECT_DEVICE")})).or(lr.object({type:hr("APP_CONNECT_OTP"),payload:fr})).or(lr.object({type:hr("APP_GET_USER"),payload:lr.optional(mr)})).or(lr.object({type:hr("APP_SIGN_OUT")})).or(lr.object({type:hr("APP_IS_CONNECTED"),payload:lr.optional(Or)})).or(lr.object({type:hr("APP_GET_CHAIN_ID")})).or(lr.object({type:hr("APP_RPC_REQUEST"),payload:Ar.or(kr).or(Sr).or(Cr).or(Mr).or(Ir).or(Pr)})).or(lr.object({type:hr("APP_UPDATE_EMAIL"),payload:gr})).or(lr.object({type:hr("APP_AWAIT_UPDATE_EMAIL")})).or(lr.object({type:hr("APP_SYNC_THEME"),payload:yr})),frameEvent:lr.object({type:hr("FRAME_SWITCH_NETWORK_ERROR"),payload:ur}).or(lr.object({type:hr("FRAME_SWITCH_NETWORK_SUCCESS")})).or(lr.object({type:hr("FRAME_CONNECT_EMAIL_ERROR"),payload:ur})).or(lr.object({type:hr("FRAME_CONNECT_EMAIL_SUCCESS"),payload:wr})).or(lr.object({type:hr("FRAME_CONNECT_OTP_ERROR"),payload:ur})).or(lr.object({type:hr("FRAME_CONNECT_OTP_SUCCESS")})).or(lr.object({type:hr("FRAME_CONNECT_DEVICE_ERROR"),payload:ur})).or(lr.object({type:hr("FRAME_CONNECT_DEVICE_SUCCESS")})).or(lr.object({type:hr("FRAME_GET_USER_ERROR"),payload:ur})).or(lr.object({type:hr("FRAME_GET_USER_SUCCESS"),payload:br})).or(lr.object({type:hr("FRAME_SIGN_OUT_ERROR"),payload:ur})).or(lr.object({type:hr("FRAME_SIGN_OUT_SUCCESS")})).or(lr.object({type:hr("FRAME_IS_CONNECTED_ERROR"),payload:ur})).or(lr.object({type:hr("FRAME_IS_CONNECTED_SUCCESS"),payload:vr})).or(lr.object({type:hr("FRAME_GET_CHAIN_ID_ERROR"),payload:ur})).or(lr.object({type:hr("FRAME_GET_CHAIN_ID_SUCCESS"),payload:Er})).or(lr.object({type:hr("FRAME_RPC_REQUEST_ERROR"),payload:ur})).or(lr.object({type:hr("FRAME_RPC_REQUEST_SUCCESS"),payload:_r})).or(lr.object({type:hr("FRAME_SESSION_UPDATE"),payload:Or})).or(lr.object({type:hr("FRAME_UPDATE_EMAIL_ERROR"),payload:ur})).or(lr.object({type:hr("FRAME_UPDATE_EMAIL_SUCCESS")})).or(lr.object({type:hr("FRAME_AWAIT_UPDATE_EMAIL_ERROR"),payload:ur})).or(lr.object({type:hr("FRAME_AWAIT_UPDATE_EMAIL_SUCCESS"),payload:xr})).or(lr.object({type:hr("FRAME_SYNC_THEME_ERROR"),payload:ur})).or(lr.object({type:hr("FRAME_SYNC_THEME_SUCCESS")}))},Nr={set(t,e){localStorage.setItem(`${nt.STORAGE_KEY}${t}`,e)},get:t=>localStorage.getItem(`${nt.STORAGE_KEY}${t}`),delete(t){localStorage.removeItem(`${nt.STORAGE_KEY}${t}`)}},Rr=["ASIA/SHANGHAI","ASIA/URUMQI","ASIA/CHONGQING","ASIA/HARBIN","ASIA/KASHGAR","ASIA/MACAU","ASIA/HONG_KONG","ASIA/MACAO","ASIA/BEIJING","ASIA/HARBIN"],Br=3e4,jr={getBlockchainApiUrl(){try{const{timeZone:t}=(new Intl.DateTimeFormat).resolvedOptions(),e=t.toUpperCase();return Rr.includes(e)?"https://rpc.walletconnect.org":"https://rpc.walletconnect.com"}catch{return!1}},checkIfAllowedToTriggerEmail(){const t=Nr.get(nt.LAST_EMAIL_LOGIN_TIME);if(t){const e=Date.now()-Number(t);if(e<Br){const t=Math.ceil((Br-e)/1e3);throw new Error(`Please try again after ${t} seconds`)}}},getTimeToNextEmailLogin(){const t=Nr.get(nt.LAST_EMAIL_LOGIN_TIME);if(t){const e=Date.now()-Number(t);if(e<Br)return Math.ceil((Br-e)/1e3)}return 0}};class Lr{constructor(t,e=!1){if(this.iframe=null,this.rpcUrl=jr.getBlockchainApiUrl(),this.events={onFrameEvent:t=>{window.addEventListener("message",(({data:e})=>{if(!e.type?.includes(nt.FRAME_EVENT_KEY))return;const r=Tr.frameEvent.parse(e);t(r)}))},onAppEvent:t=>{window.addEventListener("message",(({data:e})=>{if(!e.type?.includes(nt.APP_EVENT_KEY))return;const r=Tr.appEvent.parse(e);t(r)}))},postAppEvent:t=>{if(!this.iframe?.contentWindow)throw new Error("W3mFrame: iframe is not set");Tr.appEvent.parse(t),window.postMessage(t),this.iframe.contentWindow.postMessage(t,"*")},postFrameEvent:t=>{if(!parent)throw new Error("W3mFrame: parent is not set");Tr.frameEvent.parse(t),parent.postMessage(t,"*")}},this.projectId=t,this.frameLoadPromise=new Promise(((t,e)=>{this.frameLoadPromiseResolver={resolve:t,reject:e}})),e){this.frameLoadPromise=new Promise(((t,e)=>{this.frameLoadPromiseResolver={resolve:t,reject:e}}));const e=document.createElement("iframe");e.id="w3m-iframe",e.src=`${nt.SECURE_SITE_SDK}?projectId=${t}`,e.style.position="fixed",e.style.zIndex="999999",e.style.display="none",e.style.opacity="0",e.style.borderRadius="clamp(0px, var(--wui-border-radius-l), 44px)",document.body.appendChild(e),this.iframe=e,this.iframe.onload=()=>{this.frameLoadPromiseResolver?.resolve(void 0)},this.iframe.onerror=()=>{this.frameLoadPromiseResolver?.reject("Unable to load email login dependency")}}}get networks(){const t=[1,5,11155111,10,420,42161,421613,137,80001,42220,1313161554,1313161555,56,97,43114,43113,324,280,100,8453,84531,7777777,999].map((t=>({[t]:{rpcUrl:`${this.rpcUrl}/v1/?chainId=eip155:${t}&projectId=${this.projectId}`,chainId:t}})));return Object.assign({},...t)}}class Ur{constructor(t){this.connectEmailResolver=void 0,this.connectDeviceResolver=void 0,this.connectOtpResolver=void 0,this.connectResolver=void 0,this.disconnectResolver=void 0,this.isConnectedResolver=void 0,this.getChainIdResolver=void 0,this.switchChainResolver=void 0,this.rpcRequestResolver=void 0,this.updateEmailResolver=void 0,this.awaitUpdateEmailResolver=void 0,this.syncThemeResolver=void 0,this.w3mFrame=new Lr(t,!0),this.w3mFrame.events.onFrameEvent((t=>{switch(console.log("💻 received",t),t.type){case nt.FRAME_CONNECT_EMAIL_SUCCESS:return this.onConnectEmailSuccess(t);case nt.FRAME_CONNECT_EMAIL_ERROR:return this.onConnectEmailError(t);case nt.FRAME_CONNECT_DEVICE_SUCCESS:return this.onConnectDeviceSuccess();case nt.FRAME_CONNECT_DEVICE_ERROR:return this.onConnectDeviceError(t);case nt.FRAME_CONNECT_OTP_SUCCESS:return this.onConnectOtpSuccess();case nt.FRAME_CONNECT_OTP_ERROR:return this.onConnectOtpError(t);case nt.FRAME_GET_USER_SUCCESS:return this.onConnectSuccess(t);case nt.FRAME_GET_USER_ERROR:return this.onConnectError(t);case nt.FRAME_IS_CONNECTED_SUCCESS:return this.onIsConnectedSuccess(t);case nt.FRAME_IS_CONNECTED_ERROR:return this.onIsConnectedError(t);case nt.FRAME_GET_CHAIN_ID_SUCCESS:return this.onGetChainIdSuccess(t);case nt.FRAME_GET_CHAIN_ID_ERROR:return this.onGetChainIdError(t);case nt.FRAME_SIGN_OUT_SUCCESS:return this.onSignOutSuccess();case nt.FRAME_SIGN_OUT_ERROR:return this.onSignOutError(t);case nt.FRAME_SWITCH_NETWORK_SUCCESS:return this.onSwitchChainSuccess();case nt.FRAME_SWITCH_NETWORK_ERROR:return this.onSwitchChainError(t);case nt.FRAME_RPC_REQUEST_SUCCESS:return this.onRpcRequestSuccess(t);case nt.FRAME_RPC_REQUEST_ERROR:return this.onRpcRequestError(t);case nt.FRAME_SESSION_UPDATE:return this.onSessionUpdate(t);case nt.FRAME_UPDATE_EMAIL_SUCCESS:return this.onUpdateEmailSuccess();case nt.FRAME_UPDATE_EMAIL_ERROR:return this.onUpdateEmailError(t);case nt.FRAME_AWAIT_UPDATE_EMAIL_SUCCESS:return this.onAwaitUpdateEmailSuccess(t);case nt.FRAME_AWAIT_UPDATE_EMAIL_ERROR:return this.onAwaitUpdateEmailError(t);case nt.FRAME_SYNC_THEME_SUCCESS:return this.onSyncThemeSuccess();case nt.FRAME_SYNC_THEME_ERROR:return this.onSyncThemeError(t);default:return null}}))}getLoginEmailUsed(){return Boolean(Nr.get(nt.EMAIL_LOGIN_USED_KEY))}getEmail(){return Nr.get(nt.EMAIL)}async connectEmail(t){return await this.w3mFrame.frameLoadPromise,jr.checkIfAllowedToTriggerEmail(),this.w3mFrame.events.postAppEvent({type:nt.APP_CONNECT_EMAIL,payload:t}),new Promise(((t,e)=>{this.connectEmailResolver={resolve:t,reject:e}}))}async connectDevice(){return await this.w3mFrame.frameLoadPromise,this.w3mFrame.events.postAppEvent({type:nt.APP_CONNECT_DEVICE}),new Promise(((t,e)=>{this.connectDeviceResolver={resolve:t,reject:e}}))}async connectOtp(t){return await this.w3mFrame.frameLoadPromise,this.w3mFrame.events.postAppEvent({type:nt.APP_CONNECT_OTP,payload:t}),new Promise(((t,e)=>{this.connectOtpResolver={resolve:t,reject:e}}))}async isConnected(){return await this.w3mFrame.frameLoadPromise,this.w3mFrame.events.postAppEvent({type:nt.APP_IS_CONNECTED,payload:void 0}),new Promise(((t,e)=>{this.isConnectedResolver={resolve:t,reject:e}}))}async getChainId(){return await this.w3mFrame.frameLoadPromise,this.w3mFrame.events.postAppEvent({type:nt.APP_GET_CHAIN_ID}),new Promise(((t,e)=>{this.getChainIdResolver={resolve:t,reject:e}}))}async updateEmail(t){return await this.w3mFrame.frameLoadPromise,jr.checkIfAllowedToTriggerEmail(),this.w3mFrame.events.postAppEvent({type:nt.APP_UPDATE_EMAIL,payload:t}),new Promise(((t,e)=>{this.updateEmailResolver={resolve:t,reject:e}}))}async awaitUpdateEmail(){return await this.w3mFrame.frameLoadPromise,this.w3mFrame.events.postAppEvent({type:nt.APP_AWAIT_UPDATE_EMAIL}),new Promise(((t,e)=>{this.awaitUpdateEmailResolver={resolve:t,reject:e}}))}async syncTheme(t){return await this.w3mFrame.frameLoadPromise,this.w3mFrame.events.postAppEvent({type:nt.APP_SYNC_THEME,payload:t}),new Promise(((t,e)=>{this.syncThemeResolver={resolve:t,reject:e}}))}async connect(t){return await this.w3mFrame.frameLoadPromise,this.w3mFrame.events.postAppEvent({type:nt.APP_GET_USER,payload:t}),new Promise(((t,e)=>{this.connectResolver={resolve:t,reject:e}}))}async switchNetwork(t){return await this.w3mFrame.frameLoadPromise,this.w3mFrame.events.postAppEvent({type:nt.APP_SWITCH_NETWORK,payload:{chainId:t}}),new Promise(((t,e)=>{this.switchChainResolver={resolve:t,reject:e}}))}async disconnect(){return await this.w3mFrame.frameLoadPromise,this.w3mFrame.events.postAppEvent({type:nt.APP_SIGN_OUT}),new Promise(((t,e)=>{this.disconnectResolver={resolve:t,reject:e}}))}async request(t){return await this.w3mFrame.frameLoadPromise,this.w3mFrame.events.postAppEvent({type:nt.APP_RPC_REQUEST,payload:t}),new Promise(((t,e)=>{this.rpcRequestResolver={resolve:t,reject:e}}))}onRpcRequest(t){this.w3mFrame.events.onAppEvent((e=>{e.type.includes(nt.RPC_METHOD_KEY)&&t(e)}))}onRpcResponse(t){this.w3mFrame.events.onFrameEvent((e=>{e.type.includes(nt.RPC_METHOD_KEY)&&t(e)}))}onIsConnected(t){this.w3mFrame.events.onFrameEvent((e=>{e.type===nt.FRAME_IS_CONNECTED_SUCCESS&&t()}))}onConnectEmailSuccess(t){this.connectEmailResolver?.resolve(t.payload),this.setNewLastEmailLoginTime()}onConnectEmailError(t){this.connectEmailResolver?.reject(t.payload.message)}onConnectDeviceSuccess(){this.connectDeviceResolver?.resolve(void 0)}onConnectDeviceError(t){this.connectDeviceResolver?.reject(t.payload.message)}onConnectOtpSuccess(){this.connectOtpResolver?.resolve(void 0)}onConnectOtpError(t){this.connectOtpResolver?.reject(t.payload.message)}onConnectSuccess(t){this.setEmailLoginSuccess(t.payload.email),this.connectResolver?.resolve(t.payload)}onConnectError(t){this.connectResolver?.reject(t.payload.message)}onIsConnectedSuccess(t){this.isConnectedResolver?.resolve(t.payload)}onIsConnectedError(t){this.isConnectedResolver?.reject(t.payload.message)}onGetChainIdSuccess(t){this.getChainIdResolver?.resolve(t.payload)}onGetChainIdError(t){this.getChainIdResolver?.reject(t.payload.message)}onSignOutSuccess(){this.disconnectResolver?.resolve(void 0),Nr.delete(nt.EMAIL_LOGIN_USED_KEY),Nr.delete(nt.EMAIL)}onSignOutError(t){this.disconnectResolver?.reject(t.payload.message)}onSwitchChainSuccess(){this.switchChainResolver?.resolve(void 0)}onSwitchChainError(t){this.switchChainResolver?.reject(t.payload.message)}onRpcRequestSuccess(t){this.rpcRequestResolver?.resolve(t.payload)}onRpcRequestError(t){this.rpcRequestResolver?.reject(t.payload.message)}onSessionUpdate(t){const{payload:e}=t}onUpdateEmailSuccess(){this.updateEmailResolver?.resolve(void 0),this.setNewLastEmailLoginTime()}onUpdateEmailError(t){this.updateEmailResolver?.reject(t.payload.message)}onAwaitUpdateEmailSuccess(t){this.setEmailLoginSuccess(t.payload.email),this.awaitUpdateEmailResolver?.resolve(t.payload)}onAwaitUpdateEmailError(t){this.awaitUpdateEmailResolver?.reject(t.payload.message)}onSyncThemeSuccess(){this.syncThemeResolver?.resolve(void 0)}onSyncThemeError(t){this.syncThemeResolver?.reject(t.payload.message)}setNewLastEmailLoginTime(){Nr.set(nt.LAST_EMAIL_LOGIN_TIME,Date.now().toString())}setEmailLoginSuccess(t){Nr.set(nt.EMAIL,t),Nr.set(nt.EMAIL_LOGIN_USED_KEY,"true"),Nr.delete(nt.LAST_EMAIL_LOGIN_TIME)}}var Dr=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let $r=class extends a.oi{constructor(){super(...arguments),this.email=s.RouterController.state.data?.email,this.emailConnector=s.ConnectorController.getEmailConnector(),this.loading=!1,this.timeoutTimeLeft=jr.getTimeToNextEmailLogin(),this.error=""}firstUpdated(){this.startOTPTimeout()}disconnectedCallback(){clearTimeout(this.OTPTimeout)}render(){if(!this.email)throw new Error("w3m-email-verify-otp-view: No email provided");const t=Boolean(this.timeoutTimeLeft);return a.dy`
      <wui-flex
        flexDirection="column"
        alignItems="center"
        .padding=${["l","0","l","0"]}
        gap="l"
      >
        <wui-flex flexDirection="column" alignItems="center">
          <wui-text variant="paragraph-400" color="fg-100"> Enter the code we sent to </wui-text>
          <wui-text variant="paragraph-500" color="fg-100">${this.email}</wui-text>
        </wui-flex>

        <wui-text variant="small-400" color="fg-200">The code expires in 20 minutes</wui-text>

        ${this.loading?a.dy`<wui-loading-spinner size="xl" color="accent-100"></wui-loading-spinner>`:a.dy` <wui-flex flexDirection="column" alignItems="center" gap="xs">
              <wui-otp
                dissabled
                length="6"
                @inputChange=${this.onOtpInputChange.bind(this)}
              ></wui-otp>
              ${this.error?a.dy`<wui-text variant="small-400" color="error-100"
                    >${this.error}. Try Again</wui-text
                  >`:null}
            </wui-flex>`}

        <wui-flex alignItems="center">
          <wui-text variant="small-400" color="fg-200">Didn't receive it?</wui-text>
          <wui-link @click=${this.onResendCode.bind(this)} .disabled=${t}>
            Resend ${t?`in ${this.timeoutTimeLeft}s`:"Code"}
          </wui-link>
        </wui-flex>
      </wui-flex>
    `}startOTPTimeout(){this.OTPTimeout=setInterval((()=>{this.timeoutTimeLeft>0?this.timeoutTimeLeft=jr.getTimeToNextEmailLogin():clearInterval(this.OTPTimeout)}),1e3)}async onOtpInputChange(t){try{if(!this.loading){const e=t.detail;this.emailConnector&&6===e.length&&(this.loading=!0,await this.emailConnector.provider.connectOtp({otp:e}),await s.ConnectionController.connectExternal(this.emailConnector),s.IN.close(),s.Xs.sendEvent({type:"track",event:"CONNECT_SUCCESS",properties:{method:"email"}}))}}catch(t){this.error=s.j1.parseError(t),this.loading=!1}}async onResendCode(){try{if(!this.loading&&!this.timeoutTimeLeft){const t=s.ConnectorController.getEmailConnector();if(!t||!this.email)throw new Error("w3m-email-login-widget: Unable to resend email");this.loading=!0,await t.provider.connectEmail({email:this.email}),this.startOTPTimeout(),s.SnackController.showSuccess("Code email resent")}}catch(t){s.SnackController.showError(t)}finally{this.loading=!1}}};$r.styles=rt,Dr([(0,c.SB)()],$r.prototype,"loading",void 0),Dr([(0,c.SB)()],$r.prototype,"timeoutTimeLeft",void 0),Dr([(0,c.SB)()],$r.prototype,"error",void 0),$r=Dr([(0,o.customElement)("w3m-email-verify-otp-view")],$r);const Fr=a.iv`
  wui-icon-box {
    height: var(--wui-icon-box-size-xl);
    width: var(--wui-icon-box-size-xl);
  }
`;var zr=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Hr=class extends a.oi{constructor(){super(),this.email=s.RouterController.state.data?.email,this.emailConnector=s.ConnectorController.getEmailConnector(),this.loading=!1,this.listenForDeviceApproval()}render(){if(!this.email)throw new Error("w3m-email-verify-device-view: No email provided");if(!this.emailConnector)throw new Error("w3m-email-verify-device-view: No email connector provided");return a.dy`
      <wui-flex
        flexDirection="column"
        alignItems="center"
        .padding=${["xxl","s","xxl","s"]}
        gap="l"
      >
        <wui-icon-box
          size="xl"
          iconcolor="accent-100"
          backgroundcolor="accent-100"
          icon="verify"
          background="opaque"
        ></wui-icon-box>

        <wui-flex flexDirection="column" alignItems="center" gap="s">
          <wui-flex flexDirection="column" alignItems="center">
            <wui-text variant="paragraph-400" color="fg-100">
              Approve the login link we sent to
            </wui-text>
            <wui-text variant="paragraph-400" color="fg-100"><b>${this.email}</b></wui-text>
          </wui-flex>

          <wui-text variant="small-400" color="fg-200" align="center">
            The code expires in 20 minutes
          </wui-text>

          <wui-flex alignItems="center" id="w3m-resend-section">
            <wui-text variant="small-400" color="fg-100" align="center">
              Didn't receive it?
            </wui-text>
            <wui-link @click=${this.onResendCode.bind(this)} .disabled=${this.loading}>
              Resend email
            </wui-link>
          </wui-flex>
        </wui-flex>
      </wui-flex>
    `}async listenForDeviceApproval(){this.emailConnector&&(await this.emailConnector.provider.connectDevice(),s.RouterController.replace("EmailVerifyOtp",{email:this.email}))}async onResendCode(){try{if(!this.loading){if(!this.emailConnector||!this.email)throw new Error("w3m-email-login-widget: Unable to resend email");this.loading=!0,await this.emailConnector.provider.connectEmail({email:this.email}),s.SnackController.showSuccess("Code email resent")}}catch(t){s.SnackController.showError(t)}finally{this.loading=!1}}};Hr.styles=Fr,zr([(0,c.SB)()],Hr.prototype,"loading",void 0),Hr=zr([(0,o.customElement)("w3m-email-verify-device-view")],Hr);const Wr=a.iv`
  div {
    width: 100%;
    height: 400px;
  }

  [data-ready='false'] {
    transform: scale(1.05);
  }

  @media (max-width: 430px) {
    [data-ready='false'] {
      transform: translateY(-50px);
    }
  }
`;var qr=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Vr=class extends a.oi{constructor(){super(),this.bodyObserver=void 0,this.unsubscribe=[],this.iframe=document.getElementById("w3m-iframe"),this.ready=!1,this.unsubscribe.push(s.IN.subscribeKey("open",(t=>{t||this.onHideIframe()})))}disconnectedCallback(){this.unsubscribe.forEach((t=>t())),this.bodyObserver?.unobserve(window.document.body)}firstUpdated(){this.iframe.style.display="block";const t=this.renderRoot.querySelector("div");this.bodyObserver=new ResizeObserver((()=>{const e=t?.getBoundingClientRect(),r=e??{left:0,top:0,width:0,height:0};this.iframe.style.width=`${r.width}px`,this.iframe.style.height=r.height-10+"px",this.iframe.style.left=`${r.left}px`,this.iframe.style.top=`${r.top+5}px`,this.ready=!0})),this.bodyObserver.observe(window.document.body)}render(){return this.ready&&this.onShowIframe(),a.dy`<div data-ready=${this.ready}></div>`}onShowIframe(){const t=window.innerWidth<=430;this.iframe.animate([{opacity:0,transform:t?"translateY(50px)":"scale(.95)"},{opacity:1,transform:t?"translateY(0)":"scale(1)"}],{duration:200,easing:"ease",fill:"forwards",delay:300})}async onHideIframe(){await this.iframe.animate([{opacity:1},{opacity:0}],{duration:200,easing:"ease",fill:"forwards"}).finished,this.iframe.style.display="none"}};Vr.styles=Wr,qr([(0,c.SB)()],Vr.prototype,"ready",void 0),Vr=qr([(0,o.customElement)("w3m-approve-transaction-view")],Vr);let Gr=class extends a.oi{render(){return a.dy`
      <wui-flex flexDirection="column" alignItems="center" gap="xl" padding="xl">
        <wui-text variant="paragraph-400" color="fg-100">Follow the instructions on</wui-text>
        <wui-chip
          icon="externalLink"
          variant="fill"
          href=${s.bq.SECURE_SITE_DASHBOARD}
          imageSrc=${s.bq.SECURE_SITE_FAVICON}
        >
        </wui-chip>
        <wui-text variant="small-400" color="fg-200">
          You will have to reconnect for security reasons
        </wui-text>
      </wui-flex>
    `}};Gr=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o}([(0,o.customElement)("w3m-upgrade-wallet-view")],Gr);const{D:Kr}=l._$LH;class Zr{constructor(t){}get _$AU(){return this._$AM._$AU}_$AT(t,e,r){this._$Ct=t,this._$AM=e,this._$Ci=r}_$AS(t,e){return this.update(t,e)}update(t,e){return this.render(...e)}}const Jr=(t,e)=>{const r=t._$AN;if(void 0===r)return!1;for(const t of r)t._$AO?.(e,!1),Jr(t,e);return!0},Qr=t=>{let e,r;do{if(void 0===(e=t._$AM))break;r=e._$AN,r.delete(t),t=e}while(0===r?.size)},Yr=t=>{for(let e;e=t._$AM;t=e){let r=e._$AN;if(void 0===r)e._$AN=r=new Set;else if(r.has(t))break;r.add(t),en(e)}};function Xr(t){void 0!==this._$AN?(Qr(this),this._$AM=t,Yr(this)):this._$AM=t}function tn(t,e=!1,r=0){const n=this._$AH,i=this._$AN;if(void 0!==i&&0!==i.size)if(e)if(Array.isArray(n))for(let t=r;t<n.length;t++)Jr(n[t],!1),Qr(n[t]);else null!=n&&(Jr(n,!1),Qr(n));else Jr(this,t)}const en=t=>{2==t.type&&(t._$AP??=tn,t._$AQ??=Xr)};class rn extends Zr{constructor(){super(...arguments),this._$AN=void 0}_$AT(t,e,r){super._$AT(t,e,r),Yr(this),this.isConnected=t._$AU}_$AO(t,e=!0){t!==this.isConnected&&(this.isConnected=t,t?this.reconnected?.():this.disconnected?.()),e&&(Jr(this,t),Qr(this))}setValue(t){if((t=>void 0===this._$Ct.strings)())this._$Ct._$AI(t,this);else{const e=[...this._$Ct._$AH];e[this._$Ci]=t,this._$Ct._$AI(e,this,0)}}disconnected(){}reconnected(){}}const nn=()=>new sn;class sn{}const on=new WeakMap,an=(t=>(...e)=>({_$litDirective$:t,values:e}))(class extends rn{render(t){return l.Ld}update(t,[e]){const r=e!==this.G;return r&&void 0!==this.G&&this.ot(void 0),(r||this.rt!==this.lt)&&(this.G=e,this.ct=t.options?.host,this.ot(this.lt=t.element)),l.Ld}ot(t){if("function"==typeof this.G){const e=this.ct??globalThis;let r=on.get(e);void 0===r&&(r=new WeakMap,on.set(e,r)),void 0!==r.get(this.G)&&this.G.call(this.ct,void 0),r.set(this.G,t),void 0!==t&&this.G.call(this.ct,t)}else this.G.value=t}get rt(){return"function"==typeof this.G?on.get(this.ct??globalThis)?.get(this.G):this.G?.value}disconnected(){this.rt===this.lt&&this.ot(void 0)}reconnected(){this.ot(this.lt)}}),cn=a.iv`
  wui-email-input {
    width: 100%;
  }

  form {
    width: 100%;
    display: block;
    position: relative;
  }
`;var ln=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let un=class extends a.oi{constructor(){super(...arguments),this.formRef=nn(),this.initialValue=s.RouterController.state.data?.email??"",this.email="",this.loading=!1}firstUpdated(){this.formRef.value?.addEventListener("keydown",(t=>{"Enter"===t.key&&this.onSubmitEmail(t)}))}render(){const t=!this.loading&&this.email.length>3&&this.email!==this.initialValue;return a.dy`
      <wui-flex flexDirection="column" padding="m" gap="m">
        <form ${an(this.formRef)} @submit=${this.onSubmitEmail.bind(this)}>
          <wui-email-input
            value=${this.initialValue}
            .disabled=${this.loading}
            @inputChange=${this.onEmailInputChange.bind(this)}
          >
          </wui-email-input>
          <input type="submit" hidden />
        </form>

        <wui-flex gap="s">
          <wui-button size="md" variant="shade" fullWidth @click=${s.RouterController.goBack}>
            Cancel
          </wui-button>

          <wui-button
            size="md"
            variant="fill"
            fullWidth
            @click=${this.onSubmitEmail.bind(this)}
            .disabled=${!t}
            .loading=${this.loading}
          >
            Save
          </wui-button>
        </wui-flex>
      </wui-flex>
    `}onEmailInputChange(t){this.email=t.detail}async onSubmitEmail(t){try{if(this.loading)return;this.loading=!0,t.preventDefault();const e=s.ConnectorController.getEmailConnector();if(!e)throw new Error("w3m-update-email-wallet: Email connector not found");await e.provider.updateEmail({email:this.email}),s.RouterController.replace("UpdateEmailWalletWaiting",{email:this.email})}catch(t){s.SnackController.showError(t),this.loading=!1}}};un.styles=cn,ln([(0,c.SB)()],un.prototype,"email",void 0),ln([(0,c.SB)()],un.prototype,"loading",void 0),un=ln([(0,o.customElement)("w3m-update-email-wallet-view")],un);const hn=a.iv`
  wui-icon-box {
    height: var(--wui-icon-box-size-xl);
    width: var(--wui-icon-box-size-xl);
  }
`;var dn=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let pn=class extends a.oi{constructor(){super(),this.email=s.RouterController.state.data?.email,this.emailConnector=s.ConnectorController.getEmailConnector(),this.loading=!1,this.listenForEmailUpdateApproval()}render(){if(!this.email)throw new Error("w3m-update-email-wallet-waiting-view: No email provided");if(!this.emailConnector)throw new Error("w3m-update-email-wallet-waiting-view: No email connector provided");return a.dy`
      <wui-flex
        flexDirection="column"
        alignItems="center"
        .padding=${["xxl","s","xxl","s"]}
        gap="l"
      >
        <wui-icon-box
          size="xl"
          iconcolor="accent-100"
          backgroundcolor="accent-100"
          icon="mail"
          background="opaque"
        ></wui-icon-box>

        <wui-flex flexDirection="column" alignItems="center" gap="s">
          <wui-flex flexDirection="column" alignItems="center">
            <wui-text variant="paragraph-400" color="fg-100">
              Approve verification link we sent to
            </wui-text>
            <wui-text variant="paragraph-400" color="fg-100">${this.email}</wui-text>
          </wui-flex>

          <wui-text variant="small-400" color="fg-200" align="center">
            You will receive an approval request on your former mail to confirm the new one
          </wui-text>

          <wui-flex alignItems="center" id="w3m-resend-section">
            <wui-text variant="small-400" color="fg-100" align="center">
              Didn't receive it?
            </wui-text>
            <wui-link @click=${this.onResendCode.bind(this)} .disabled=${this.loading}>
              Resend email
            </wui-link>
          </wui-flex>
        </wui-flex>
      </wui-flex>
    `}async listenForEmailUpdateApproval(){this.emailConnector&&(await this.emailConnector.provider.awaitUpdateEmail(),s.RouterController.replace("Account"),s.SnackController.showSuccess("Email updated"))}async onResendCode(){try{if(!this.loading){if(!this.emailConnector||!this.email)throw new Error("w3m-update-email-wallet-waiting-view: Unable to resend email");this.loading=!0,await this.emailConnector.provider.updateEmail({email:this.email}),this.listenForEmailUpdateApproval(),s.SnackController.showSuccess("Code email resent")}}catch(t){s.SnackController.showError(t)}finally{this.loading=!1}}};pn.styles=hn,dn([(0,c.SB)()],pn.prototype,"loading",void 0),pn=dn([(0,o.customElement)("w3m-update-email-wallet-waiting-view")],pn);const fn=a.iv`
  wui-grid {
    max-height: clamp(360px, 400px, 80vh);
    overflow: scroll;
    scrollbar-width: none;
    grid-auto-rows: min-content;
    grid-template-columns: repeat(auto-fill, 76px);
  }

  @media (max-width: 435px) {
    wui-grid {
      grid-template-columns: repeat(auto-fill, 77px);
    }
  }

  wui-grid[data-scroll='false'] {
    overflow: hidden;
  }

  wui-grid::-webkit-scrollbar {
    display: none;
  }

  wui-loading-spinner {
    padding-top: var(--wui-spacing-l);
    padding-bottom: var(--wui-spacing-l);
    justify-content: center;
    grid-column: 1 / span 4;
  }
`;function mn(t){const{connectors:e}=s.ConnectorController.state,r=e.filter((t=>"ANNOUNCED"===t.type)).reduce(((t,e)=>e.info?.rdns?(t[e.info.rdns]=!0,t):t),{});return t.map((t=>({...t,installed:Boolean(t.rdns)&&Boolean(r[t.rdns??""])}))).sort(((t,e)=>Number(e.installed)-Number(t.installed)))}var gn=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};const yn="local-paginator";let wn=class extends a.oi{constructor(){super(),this.unsubscribe=[],this.paginationObserver=void 0,this.initial=!s.ApiController.state.wallets.length,this.wallets=s.ApiController.state.wallets,this.recommended=s.ApiController.state.recommended,this.featured=s.ApiController.state.featured,this.unsubscribe.push(s.ApiController.subscribeKey("wallets",(t=>this.wallets=t)),s.ApiController.subscribeKey("recommended",(t=>this.recommended=t)),s.ApiController.subscribeKey("featured",(t=>this.featured=t)))}firstUpdated(){this.initialFetch(),this.createPaginationObserver()}disconnectedCallback(){this.unsubscribe.forEach((t=>t())),this.paginationObserver?.disconnect()}render(){return a.dy`
      <wui-grid
        data-scroll=${!this.initial}
        .padding=${["0","s","s","s"]}
        columnGap="xxs"
        rowGap="l"
        justifyContent="space-between"
      >
        ${this.initial?this.shimmerTemplate(16):this.walletsTemplate()}
        ${this.paginationLoaderTemplate()}
      </wui-grid>
    `}async initialFetch(){const t=this.shadowRoot?.querySelector("wui-grid");this.initial&&t&&(await s.ApiController.fetchWallets({page:1}),await t.animate([{opacity:1},{opacity:0}],{duration:200,fill:"forwards",easing:"ease"}).finished,this.initial=!1,t.animate([{opacity:0},{opacity:1}],{duration:200,fill:"forwards",easing:"ease"}))}shimmerTemplate(t,e){return[...Array(t)].map((()=>a.dy`
        <wui-card-select-loader type="wallet" id=${u(e)}></wui-card-select-loader>
      `))}walletsTemplate(){return mn([...this.featured,...this.recommended,...this.wallets]).map((t=>a.dy`
        <wui-card-select
          imageSrc=${u(s.fz.getWalletImage(t))}
          type="wallet"
          name=${t.name}
          @click=${()=>this.onConnectWallet(t)}
          .installed=${t.installed}
        ></wui-card-select>
      `))}paginationLoaderTemplate(){const{wallets:t,recommended:e,featured:r,count:n}=s.ApiController.state,i=window.innerWidth<352?3:4,o=t.length+e.length;let a=Math.ceil(o/i)*i-o+i;return a-=t.length?r.length%i:0,0===n&&r.length>0?null:0===n||[...r,...t,...e].length<n?this.shimmerTemplate(a,yn):null}createPaginationObserver(){const t=this.shadowRoot?.querySelector(`#${yn}`);t&&(this.paginationObserver=new IntersectionObserver((([t])=>{if(t?.isIntersecting&&!this.initial){const{page:t,count:e,wallets:r}=s.ApiController.state;r.length<e&&s.ApiController.fetchWallets({page:t+1})}})),this.paginationObserver.observe(t))}onConnectWallet(t){const{connectors:e}=s.ConnectorController.state,r=e.find((({explorerId:e})=>e===t.id));r?s.RouterController.push("ConnectingExternal",{connector:r}):s.RouterController.push("ConnectingWalletConnect",{wallet:t})}};wn.styles=fn,gn([(0,c.SB)()],wn.prototype,"initial",void 0),gn([(0,c.SB)()],wn.prototype,"wallets",void 0),gn([(0,c.SB)()],wn.prototype,"recommended",void 0),gn([(0,c.SB)()],wn.prototype,"featured",void 0),wn=gn([(0,o.customElement)("w3m-all-wallets-list")],wn);const bn=a.iv`
  wui-grid,
  wui-loading-spinner,
  wui-flex {
    height: 360px;
  }

  wui-grid {
    overflow: scroll;
    scrollbar-width: none;
    grid-auto-rows: min-content;
  }

  wui-grid[data-scroll='false'] {
    overflow: hidden;
  }

  wui-grid::-webkit-scrollbar {
    display: none;
  }

  wui-loading-spinner {
    justify-content: center;
    align-items: center;
  }
`;var vn=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let En=class extends a.oi{constructor(){super(...arguments),this.prevQuery="",this.loading=!0,this.query=""}render(){return this.onSearch(),this.loading?a.dy`<wui-loading-spinner color="accent-100"></wui-loading-spinner>`:this.walletsTemplate()}async onSearch(){this.query!==this.prevQuery&&(this.prevQuery=this.query,this.loading=!0,await s.ApiController.searchWallet({search:this.query}),this.loading=!1)}walletsTemplate(){const{search:t}=s.ApiController.state,e=mn(t);return t.length?a.dy`
      <wui-grid
        .padding=${["0","s","s","s"]}
        gridTemplateColumns="repeat(4, 1fr)"
        rowGap="l"
        columnGap="xs"
      >
        ${e.map((t=>a.dy`
            <wui-card-select
              imageSrc=${u(s.fz.getWalletImage(t))}
              type="wallet"
              name=${t.name}
              @click=${()=>this.onConnectWallet(t)}
              .installed=${t.installed}
            ></wui-card-select>
          `))}
      </wui-grid>
    `:a.dy`
        <wui-flex justifyContent="center" alignItems="center" gap="s" flexDirection="column">
          <wui-icon-box
            size="lg"
            iconColor="fg-200"
            backgroundColor="fg-300"
            icon="wallet"
            background="transparent"
          ></wui-icon-box>
          <wui-text color="fg-200" variant="paragraph-500">No Wallet found</wui-text>
        </wui-flex>
      `}onConnectWallet(t){const{connectors:e}=s.ConnectorController.state,r=e.find((({explorerId:e})=>e===t.id));r?s.RouterController.push("ConnectingExternal",{connector:r}):s.RouterController.push("ConnectingWalletConnect",{wallet:t})}};En.styles=bn,vn([(0,c.SB)()],En.prototype,"loading",void 0),vn([(0,c.Cb)()],En.prototype,"query",void 0),En=vn([(0,o.customElement)("w3m-all-wallets-search")],En);var xn=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let _n=class extends a.oi{constructor(){super(),this.platformTabs=[],this.unsubscribe=[],this.platforms=[],this.onSelectPlatfrom=void 0,this.buffering=!1,this.unsubscribe.push(s.ConnectionController.subscribeKey("buffering",(t=>this.buffering=t)))}disconnectCallback(){this.unsubscribe.forEach((t=>t()))}render(){const t=this.generateTabs();return a.dy`
      <wui-flex justifyContent="center" .padding=${["l","0","0","0"]}>
        <wui-tabs
          ?disabled=${this.buffering}
          .tabs=${t}
          .onTabChange=${this.onTabChange.bind(this)}
        ></wui-tabs>
      </wui-flex>
    `}generateTabs(){const t=this.platforms.map((t=>"browser"===t?{label:"Browser",icon:"extension",platform:"browser"}:"mobile"===t?{label:"Mobile",icon:"mobile",platform:"mobile"}:"qrcode"===t?{label:"Mobile",icon:"mobile",platform:"qrcode"}:"web"===t?{label:"Webapp",icon:"browser",platform:"web"}:"desktop"===t?{label:"Desktop",icon:"desktop",platform:"desktop"}:{label:"Browser",icon:"extension",platform:"unsupported"}));return this.platformTabs=t.map((({platform:t})=>t)),t}onTabChange(t){const e=this.platformTabs[t];e&&this.onSelectPlatfrom?.(e)}};xn([(0,c.Cb)({type:Array})],_n.prototype,"platforms",void 0),xn([(0,c.Cb)()],_n.prototype,"onSelectPlatfrom",void 0),xn([(0,c.SB)()],_n.prototype,"buffering",void 0),_n=xn([(0,o.customElement)("w3m-connecting-header")],_n);let An=class extends T{constructor(){if(super(),!this.wallet)throw new Error("w3m-connecting-wc-browser: No wallet provided");this.onConnect=this.onConnectProxy.bind(this),this.onAutoConnect=this.onConnectProxy.bind(this),s.Xs.sendEvent({type:"track",event:"SELECT_WALLET",properties:{name:this.wallet.name,platform:"browser"}})}async onConnectProxy(){try{this.error=!1;const{connectors:t}=s.ConnectorController.state,e=t.find((t=>"ANNOUNCED"===t.type&&t.info?.rdns===this.wallet?.rdns)),r=t.find((t=>"INJECTED"===t.type));e?await s.ConnectionController.connectExternal(e):r&&await s.ConnectionController.connectExternal(r),s.IN.close(),s.Xs.sendEvent({type:"track",event:"CONNECT_SUCCESS",properties:{method:"browser"}})}catch(t){s.Xs.sendEvent({type:"track",event:"CONNECT_ERROR",properties:{message:t?.message??"Unknown"}}),this.error=!0}}};An=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o}([(0,o.customElement)("w3m-connecting-wc-browser")],An);let kn=class extends T{constructor(){if(super(),!this.wallet)throw new Error("w3m-connecting-wc-desktop: No wallet provided");this.onConnect=this.onConnectProxy.bind(this),this.onRender=this.onRenderProxy.bind(this),s.Xs.sendEvent({type:"track",event:"SELECT_WALLET",properties:{name:this.wallet.name,platform:"desktop"}})}onRenderProxy(){!this.ready&&this.uri&&(this.ready=!0,this.timeout=setTimeout((()=>{this.onConnect?.()}),200))}onConnectProxy(){if(this.wallet?.desktop_link&&this.uri)try{this.error=!1;const{desktop_link:t,name:e}=this.wallet,{redirect:r,href:n}=s.j1.formatNativeUrl(t,this.uri);s.ConnectionController.setWcLinking({name:e,href:n}),s.ConnectionController.setRecentWallet(this.wallet),s.j1.openHref(r,"_self")}catch{this.error=!0}}};kn=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o}([(0,o.customElement)("w3m-connecting-wc-desktop")],kn);let Sn=class extends T{constructor(){if(super(),!this.wallet)throw new Error("w3m-connecting-wc-mobile: No wallet provided");this.onConnect=this.onConnectProxy.bind(this),this.onRender=this.onRenderProxy.bind(this),document.addEventListener("visibilitychange",this.onBuffering.bind(this)),s.Xs.sendEvent({type:"track",event:"SELECT_WALLET",properties:{name:this.wallet.name,platform:"mobile"}})}disconnectedCallback(){super.disconnectedCallback(),document.removeEventListener("visibilitychange",this.onBuffering.bind(this))}onRenderProxy(){!this.ready&&this.uri&&(this.ready=!0,this.onConnect?.())}onConnectProxy(){if(this.wallet?.mobile_link&&this.uri)try{this.error=!1;const{mobile_link:t,name:e}=this.wallet,{redirect:r,href:n}=s.j1.formatNativeUrl(t,this.uri);s.ConnectionController.setWcLinking({name:e,href:n}),s.ConnectionController.setRecentWallet(this.wallet),s.j1.openHref(r,"_self")}catch{this.error=!0}}onBuffering(){const t=s.j1.isIos();"visible"===document?.visibilityState&&!this.error&&t&&(s.ConnectionController.setBuffering(!0),setTimeout((()=>{s.ConnectionController.setBuffering(!1)}),5e3))}};Sn=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o}([(0,o.customElement)("w3m-connecting-wc-mobile")],Sn);const Cn=a.iv`
  @keyframes fadein {
    from {
      opacity: 0;
    }
    to {
      opacity: 1;
    }
  }

  wui-shimmer {
    width: 100%;
    aspect-ratio: 1 / 1;
    border-radius: clamp(0px, var(--wui-border-radius-l), 40px) !important;
  }

  wui-qr-code {
    opacity: 0;
    animation-duration: 200ms;
    animation-timing-function: ease;
    animation-name: fadein;
    animation-fill-mode: forwards;
  }
`;let Mn=class extends T{constructor(){super(),this.forceUpdate=()=>{this.requestUpdate()},window.addEventListener("resize",this.forceUpdate),s.Xs.sendEvent({type:"track",event:"SELECT_WALLET",properties:{name:this.wallet?.name??"WalletConnect",platform:"qrcode"}})}disconnectedCallback(){super.disconnectedCallback(),window.removeEventListener("resize",this.forceUpdate)}render(){return this.onRenderProxy(),a.dy`
      <wui-flex padding="xl" flexDirection="column" gap="xl" alignItems="center">
        <wui-shimmer borderRadius="l" width="100%"> ${this.qrCodeTemplate()} </wui-shimmer>

        <wui-text variant="paragraph-500" color="fg-100">
          Scan this QR Code with your phone
        </wui-text>
        ${this.copyTemplate()}
      </wui-flex>

      <w3m-mobile-download-links .wallet=${this.wallet}></w3m-mobile-download-links>
    `}onRenderProxy(){!this.ready&&this.uri&&(this.timeout=setTimeout((()=>{this.ready=!0}),200))}qrCodeTemplate(){if(!this.uri||!this.ready)return null;const t=this.getBoundingClientRect().width-40,e=this.wallet?this.wallet.name:void 0;return s.ConnectionController.setWcLinking(void 0),s.ConnectionController.setRecentWallet(this.wallet),a.dy` <wui-qr-code
      size=${t}
      theme=${s.ThemeController.state.themeMode}
      uri=${this.uri}
      imageSrc=${u(s.fz.getWalletImage(this.wallet))}
      alt=${u(e)}
    ></wui-qr-code>`}copyTemplate(){const t=!this.uri||!this.ready;return a.dy`<wui-link
      .disabled=${t}
      @click=${this.onCopyUri}
      color="fg-200"
      data-testid="copy-wc2-uri"
    >
      <wui-icon size="xs" color="fg-200" slot="iconLeft" name="copy"></wui-icon>
      Copy link
    </wui-link>`}};Mn.styles=Cn,Mn=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o}([(0,o.customElement)("w3m-connecting-wc-qrcode")],Mn);const In=a.iv`
  :host {
    display: flex;
    justify-content: center;
    gap: var(--wui-spacing-2xl);
  }

  wui-visual-thumbnail:nth-child(1) {
    z-index: 1;
  }
`;let Pn=class extends a.oi{constructor(){super(...arguments),this.dappImageUrl=s.OptionsController.state.metadata?.icons,this.walletImageUrl=s.MO.getConnectedWalletImageUrl()}firstUpdated(){const t=this.shadowRoot?.querySelectorAll("wui-visual-thumbnail");t?.[0]&&this.createAnimation(t[0],"translate(18px)"),t?.[1]&&this.createAnimation(t[1],"translate(-18px)")}render(){return a.dy`
      <wui-visual-thumbnail
        ?borderRadiusFull=${!0}
        .imageSrc=${this.dappImageUrl?.[0]}
      ></wui-visual-thumbnail>
      <wui-visual-thumbnail .imageSrc=${this.walletImageUrl}></wui-visual-thumbnail>
    `}createAnimation(t,e){t.animate([{transform:"translateX(0px)"},{transform:e}],{duration:1600,easing:"cubic-bezier(0.56, 0, 0.48, 1)",direction:"alternate",iterations:1/0})}};Pn.styles=In,Pn=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o}([(0,o.customElement)("w3m-connecting-siwe")],Pn);let On=class extends a.oi{constructor(){if(super(),this.wallet=s.RouterController.state.data?.wallet,!this.wallet)throw new Error("w3m-connecting-wc-unsupported: No wallet provided");s.Xs.sendEvent({type:"track",event:"SELECT_WALLET",properties:{name:this.wallet.name,platform:"browser"}})}render(){return a.dy`
      <wui-flex
        flexDirection="column"
        alignItems="center"
        .padding=${["3xl","xl","xl","xl"]}
        gap="xl"
      >
        <wui-wallet-image
          size="lg"
          imageSrc=${u(s.fz.getWalletImage(this.wallet))}
        ></wui-wallet-image>

        <wui-text variant="paragraph-500" color="fg-100">Not Detected</wui-text>
      </wui-flex>

      <w3m-mobile-download-links .wallet=${this.wallet}></w3m-mobile-download-links>
    `}};On=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o}([(0,o.customElement)("w3m-connecting-wc-unsupported")],On);let Tn=class extends T{constructor(){if(super(),!this.wallet)throw new Error("w3m-connecting-wc-web: No wallet provided");this.onConnect=this.onConnectProxy.bind(this),this.secondaryBtnLabel="Open",this.secondaryLabel="Open and continue in a new browser tab",this.secondaryBtnIcon="externalLink",s.Xs.sendEvent({type:"track",event:"SELECT_WALLET",properties:{name:this.wallet.name,platform:"web"}})}onConnectProxy(){if(this.wallet?.webapp_link&&this.uri)try{this.error=!1;const{webapp_link:t,name:e}=this.wallet,{redirect:r,href:n}=s.j1.formatUniversalUrl(t,this.uri);s.ConnectionController.setWcLinking({name:e,href:n}),s.ConnectionController.setRecentWallet(this.wallet),s.j1.openHref(r,"_blank")}catch{this.error=!0}}};Tn=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o}([(0,o.customElement)("w3m-connecting-wc-web")],Tn);const Nn=a.iv`
  wui-icon-link[data-hidden='true'] {
    opacity: 0 !important;
    pointer-events: none;
  }
`;var Rn=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};function Bn(){const t=s.RouterController.state.data?.connector?.name,e=s.RouterController.state.data?.wallet?.name,r=s.RouterController.state.data?.network?.name,n=e??t,i=s.ConnectorController.getConnectors();return{Connect:`Connect ${1===i.length&&"w3m-email"===i[0]?.id?"Email":""} Wallet`,Account:void 0,ConnectingExternal:n??"Connect Wallet",ConnectingWalletConnect:n??"WalletConnect",ConnectingSiwe:"Sign In",Networks:"Choose Network",SwitchNetwork:r??"Switch Network",AllWallets:"All Wallets",WhatIsANetwork:"What is a network?",WhatIsAWallet:"What is a wallet?",GetWallet:"Get a wallet",Downloads:n?`Get ${n}`:"Downloads",EmailVerifyOtp:"Confirm Email",EmailVerifyDevice:"Register Device",ApproveTransaction:"Approve Transaction",Transactions:"Activity",UpgradeEmailWallet:"Upgrade your Wallet",UpdateEmailWallet:"Edit Email",UpdateEmailWalletWaiting:"Approve Email"}}let jn=class extends a.oi{constructor(){super(),this.unsubscribe=[],this.heading=Bn()[s.RouterController.state.view],this.buffering=!1,this.showBack=!1,this.unsubscribe.push(s.RouterController.subscribeKey("view",(t=>{this.onViewChange(t),this.onHistoryChange()})),s.ConnectionController.subscribeKey("buffering",(t=>this.buffering=t)))}disconnectCallback(){this.unsubscribe.forEach((t=>t()))}render(){return a.dy`
      <wui-flex .padding=${this.getPadding()} justifyContent="space-between" alignItems="center">
        ${this.dynamicButtonTemplate()} ${this.titleTemplate()}
        <wui-icon-link
          ?disabled=${this.buffering}
          icon="close"
          @click=${this.onClose.bind(this)}
          data-testid="w3m-header-close"
        ></wui-icon-link>
      </wui-flex>
      ${this.separatorTemplate()}
    `}onWalletHelp(){s.Xs.sendEvent({type:"track",event:"CLICK_WALLET_HELP"}),s.RouterController.push("WhatIsAWallet")}async onClose(){s.yD.state.isSiweEnabled&&"success"!==s.yD.state.status&&await s.ConnectionController.disconnect(),s.IN.close()}titleTemplate(){return a.dy`<wui-text variant="paragraph-700" color="fg-100">${this.heading}</wui-text>`}dynamicButtonTemplate(){const{view:t}=s.RouterController.state,e="Connect"===t,r="ApproveTransaction"===t;return this.showBack&&!r?a.dy`<wui-icon-link
        id="dynamic"
        icon="chevronLeft"
        ?disabled=${this.buffering}
        @click=${this.onGoBack.bind(this)}
      ></wui-icon-link>`:a.dy`<wui-icon-link
      data-hidden=${!e}
      id="dynamic"
      icon="helpCircle"
      @click=${this.onWalletHelp.bind(this)}
    ></wui-icon-link>`}separatorTemplate(){return this.heading?a.dy`<wui-separator></wui-separator>`:null}getPadding(){return this.heading?["l","2l","l","2l"]:["l","2l","0","2l"]}async onViewChange(t){const e=this.shadowRoot?.querySelector("wui-text");if(e){const r=Bn()[t];await e.animate([{opacity:1},{opacity:0}],{duration:200,fill:"forwards",easing:"ease"}).finished,this.heading=r,e.animate([{opacity:0},{opacity:1}],{duration:200,fill:"forwards",easing:"ease"})}}async onHistoryChange(){const{history:t}=s.RouterController.state,e=this.shadowRoot?.querySelector("#dynamic");t.length>1&&!this.showBack&&e?(await e.animate([{opacity:1},{opacity:0}],{duration:200,fill:"forwards",easing:"ease"}).finished,this.showBack=!0,e.animate([{opacity:0},{opacity:1}],{duration:200,fill:"forwards",easing:"ease"})):t.length<=1&&this.showBack&&e&&(await e.animate([{opacity:1},{opacity:0}],{duration:200,fill:"forwards",easing:"ease"}).finished,this.showBack=!1,e.animate([{opacity:0},{opacity:1}],{duration:200,fill:"forwards",easing:"ease"}))}onGoBack(){"ConnectingSiwe"===s.RouterController.state.view?s.RouterController.push("Connect"):s.RouterController.goBack()}};jn.styles=[Nn],Rn([(0,c.SB)()],jn.prototype,"heading",void 0),Rn([(0,c.SB)()],jn.prototype,"buffering",void 0),Rn([(0,c.SB)()],jn.prototype,"showBack",void 0),jn=Rn([(0,o.customElement)("w3m-header")],jn);var Ln=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Un=class extends a.oi{constructor(){super(...arguments),this.data=[]}render(){return a.dy`
      <wui-flex flexDirection="column" alignItems="center" gap="l">
        ${this.data.map((t=>a.dy`
            <wui-flex flexDirection="column" alignItems="center" gap="xl">
              <wui-flex flexDirection="row" justifyContent="center" gap="1xs">
                ${t.images.map((t=>a.dy`<wui-visual name=${t}></wui-visual>`))}
              </wui-flex>
            </wui-flex>
            <wui-flex flexDirection="column" alignItems="center" gap="xxs">
              <wui-text variant="paragraph-500" color="fg-100" align="center">
                ${t.title}
              </wui-text>
              <wui-text variant="small-500" color="fg-200" align="center">${t.text}</wui-text>
            </wui-flex>
          `))}
      </wui-flex>
    `}};Ln([(0,c.Cb)({type:Array})],Un.prototype,"data",void 0),Un=Ln([(0,o.customElement)("w3m-help-widget")],Un);const Dn=a.iv`
  wui-flex {
    background-color: var(--wui-gray-glass-005);
  }

  a {
    text-decoration: none;
    color: var(--wui-color-fg-175);
    font-weight: 500;
  }
`;let $n=class extends a.oi{render(){const{termsConditionsUrl:t,privacyPolicyUrl:e}=s.OptionsController.state;return t||e?a.dy`
      <wui-flex .padding=${["m","s","s","s"]} justifyContent="center">
        <wui-text color="fg-250" variant="small-400" align="center">
          By connecting your wallet, you agree to our <br />
          ${this.termsTemplate()} ${this.andTemplate()} ${this.privacyTemplate()}
        </wui-text>
      </wui-flex>
    `:null}andTemplate(){const{termsConditionsUrl:t,privacyPolicyUrl:e}=s.OptionsController.state;return t&&e?"and":""}termsTemplate(){const{termsConditionsUrl:t}=s.OptionsController.state;return t?a.dy`<a href=${t}>Terms of Service</a>`:null}privacyTemplate(){const{privacyPolicyUrl:t}=s.OptionsController.state;return t?a.dy`<a href=${t}>Privacy Policy</a>`:null}};$n.styles=[Dn],$n=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o}([(0,o.customElement)("w3m-legal-footer")],$n);const Fn=a.iv`
  :host {
    display: block;
    padding: 0 var(--wui-spacing-xl) var(--wui-spacing-xl);
  }
`;var zn=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Hn=class extends a.oi{constructor(){super(...arguments),this.wallet=void 0}render(){if(!this.wallet)return this.style.display="none",null;const{name:t,app_store:e,play_store:r,chrome_store:n,homepage:i}=this.wallet,c=s.j1.isMobile(),l=s.j1.isIos(),u=s.j1.isAndroid(),h=[e,r,i,n].filter(Boolean).length>1,d=o.UiHelperUtil.getTruncateString({string:t,charsStart:12,charsEnd:0,truncate:"end"});return h&&!c?a.dy`
        <wui-cta-button
          label=${`Don't have ${d}?`}
          buttonLabel="Get"
          @click=${()=>s.RouterController.push("Downloads",{wallet:this.wallet})}
        ></wui-cta-button>
      `:!h&&i?a.dy`
        <wui-cta-button
          label=${`Don't have ${d}?`}
          buttonLabel="Get"
          @click=${this.onHomePage.bind(this)}
        ></wui-cta-button>
      `:e&&l?a.dy`
        <wui-cta-button
          label=${`Don't have ${d}?`}
          buttonLabel="Get"
          @click=${this.onAppStore.bind(this)}
        ></wui-cta-button>
      `:r&&u?a.dy`
        <wui-cta-button
          label=${`Don't have ${d}?`}
          buttonLabel="Get"
          @click=${this.onPlayStore.bind(this)}
        ></wui-cta-button>
      `:(this.style.display="none",null)}onAppStore(){this.wallet?.app_store&&s.j1.openHref(this.wallet.app_store,"_blank")}onPlayStore(){this.wallet?.play_store&&s.j1.openHref(this.wallet.play_store,"_blank")}onHomePage(){this.wallet?.homepage&&s.j1.openHref(this.wallet.homepage,"_blank")}};Hn.styles=[Fn],zn([(0,c.Cb)({type:Object})],Hn.prototype,"wallet",void 0),Hn=zn([(0,o.customElement)("w3m-mobile-download-links")],Hn);const Wn=a.iv`
  :host {
    display: block;
    position: absolute;
    opacity: 0;
    pointer-events: none;
    top: 11px;
    left: 50%;
    width: max-content;
  }
`;var qn=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};const Vn={success:{backgroundColor:"success-100",iconColor:"success-100",icon:"checkmark"},error:{backgroundColor:"error-100",iconColor:"error-100",icon:"close"}};let Gn=class extends a.oi{constructor(){super(),this.unsubscribe=[],this.timeout=void 0,this.open=s.SnackController.state.open,this.unsubscribe.push(s.SnackController.subscribeKey("open",(t=>{this.open=t,this.onOpen()})))}disconnectedCallback(){clearTimeout(this.timeout),this.unsubscribe.forEach((t=>t()))}render(){const{message:t,variant:e}=s.SnackController.state,r=Vn[e];return a.dy`
      <wui-snackbar
        message=${t}
        backgroundColor=${r.backgroundColor}
        iconColor=${r.iconColor}
        icon=${r.icon}
      ></wui-snackbar>
    `}onOpen(){clearTimeout(this.timeout),this.open?(this.animate([{opacity:0,transform:"translateX(-50%) scale(0.85)"},{opacity:1,transform:"translateX(-50%) scale(1)"}],{duration:150,fill:"forwards",easing:"ease"}),this.timeout=setTimeout((()=>s.SnackController.hide()),2500)):this.animate([{opacity:1,transform:"translateX(-50%) scale(1)"},{opacity:0,transform:"translateX(-50%) scale(0.85)"}],{duration:150,fill:"forwards",easing:"ease"})}};Gn.styles=Wn,qn([(0,c.SB)()],Gn.prototype,"open",void 0),Gn=qn([(0,o.customElement)("w3m-snackbar")],Gn);const Kn=a.iv`
  :host {
    padding: var(--wui-spacing-3xs) 0;
  }

  wui-separator {
    margin: var(--wui-spacing-s) calc(var(--wui-spacing-s) * -1);
    width: calc(100% + var(--wui-spacing-s) * 2);
  }

  wui-email-input {
    width: 100%;
  }

  form {
    width: 100%;
    display: block;
    position: relative;
    margin-bottom: var(--wui-spacing-m);
  }

  wui-icon-link,
  wui-loading-spinner {
    position: absolute;
    top: 20px;
    transform: translateY(-50%);
  }

  wui-icon-link {
    right: var(--wui-spacing-xs);
  }

  wui-loading-spinner {
    right: var(--wui-spacing-m);
  }
`;var Zn=function(t,e,r,n){var i,s=arguments.length,o=s<3?e:null===n?n=Object.getOwnPropertyDescriptor(e,r):n;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)o=Reflect.decorate(t,e,r,n);else for(var a=t.length-1;a>=0;a--)(i=t[a])&&(o=(s<3?i(o):s>3?i(e,r,o):i(e,r))||o);return s>3&&o&&Object.defineProperty(e,r,o),o};let Jn=class extends a.oi{constructor(){super(),this.unsubscribe=[],this.formRef=nn(),this.connectors=s.ConnectorController.state.connectors,this.email="",this.loading=!1,this.error="",this.unsubscribe.push(s.ConnectorController.subscribeKey("connectors",(t=>this.connectors=t)))}disconnectedCallback(){this.unsubscribe.forEach((t=>t()))}firstUpdated(){this.formRef.value?.addEventListener("keydown",(t=>{"Enter"===t.key&&this.onSubmitEmail(t)}))}render(){const t=this.connectors.length>1,e=this.connectors.find((t=>"EMAIL"===t.type)),r=!this.loading&&this.email.length>3;return e?a.dy`
      <form ${an(this.formRef)} @submit=${this.onSubmitEmail.bind(this)}>
        <wui-email-input
          .disabled=${this.loading}
          @inputChange=${this.onEmailInputChange.bind(this)}
          .errorMessage=${this.error}
        >
        </wui-email-input>

        ${r?a.dy`
              <wui-icon-link
                size="sm"
                icon="chevronRight"
                iconcolor="accent-100"
                @click=${this.onSubmitEmail.bind(this)}
              >
              </wui-icon-link>
            `:null}
        ${this.loading?a.dy`<wui-loading-spinner size="md" color="accent-100"></wui-loading-spinner>`:null}

        <input type="submit" hidden />
      </form>

      ${t?a.dy`<wui-separator text="or"></wui-separator>`:null}