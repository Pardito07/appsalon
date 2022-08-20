let paso=1;const pasoInicial=1,pasoFinal=3,cita={id:"",nombre:"",fecha:"",hora:"",servicios:[]};function iniciarApp(){mostrarSeccion(),tabs(),botonesPaginador(),paginaAnterior(),paginaSiguiente(),consultarAPI(),idCliente(),nombreCliente(),seleccionarFecha(),seleccionarHora(),mostrarResumen()}function mostrarSeccion(){const e=document.querySelector(".mostrar");e&&e.classList.remove("mostrar");const t="#paso-"+paso;document.querySelector(t).classList.add("mostrar");const o=document.querySelector(".actual");o&&o.classList.remove("actual");document.querySelector(`[data-paso="${paso}"]`).classList.add("actual")}function tabs(){document.querySelectorAll(".tabs button").forEach(e=>{e.addEventListener("click",e=>{paso=parseInt(e.target.dataset.paso),mostrarSeccion(),botonesPaginador()})})}function botonesPaginador(){const e=document.querySelector("#anterior"),t=document.querySelector("#siguiente");1===paso?(e.classList.add("ocultar"),t.classList.remove("ocultar")):3===paso?(t.classList.add("ocultar"),e.classList.remove("ocultar"),mostrarResumen()):(e.classList.remove("ocultar"),t.classList.remove("ocultar")),mostrarSeccion()}function paginaAnterior(){document.querySelector("#anterior").addEventListener("click",()=>{paso<=1||(paso--,botonesPaginador())})}function paginaSiguiente(){document.querySelector("#siguiente").addEventListener("click",()=>{paso>=3||(paso++,botonesPaginador())})}document.addEventListener("DOMContentLoaded",()=>{iniciarApp()});const serverServicios="https://evening-escarpment-67357.herokuapp.com/api/servicios";async function consultarAPI(){try{const e=""+serverServicios,t=await fetch(e);mostrarServicios(await t.json())}catch(e){console.log(e)}}function mostrarServicios(e){e.forEach(e=>{const{id:t,nombre:o,precio:a}=e,n=document.createElement("p");n.classList.add("nombre-servicio"),n.textContent=o;const r=document.createElement("p");r.classList.add("precio-servicio"),r.textContent="$"+a;const c=document.createElement("div");c.classList.add("servicio"),c.dataset.idServicio=t,c.appendChild(n),c.appendChild(r),c.onclick=()=>{seleccionarServicio(e)},document.querySelector("#servicios").appendChild(c)})}function seleccionarServicio(e){const{servicios:t}=cita,{id:o}=e;cita.servicios=[...t,e];const a=document.querySelector(`[data-id-servicio="${o}"]`);t.some(e=>e.id===o)?(a.classList.remove("seleccionado"),cita.servicios=t.filter(e=>e.id!==o)):a.classList.add("seleccionado")}function idCliente(){cita.id=document.querySelector("#id").value}function nombreCliente(){cita.nombre=document.querySelector("#nombre").value}function seleccionarFecha(){const e=document.querySelector("#fecha");e.addEventListener("input",t=>{const o=new Date(t.target.value).getUTCDay();[0,6].includes(o)?(e.value="",mostrarAlerta("Fines de semana no permitidos","error",".formulario")):cita.fecha=t.target.value})}function seleccionarHora(){document.querySelector("#hora").addEventListener("input",e=>{const t=e.target.value.split(":")[0];t<10||t>16?(e.target.value="",mostrarAlerta("Hora no válida","error",".formulario")):cita.hora=e.target.value})}function mostrarAlerta(e,t,o,a=!0){const n=document.querySelector(".alerta");n&&n.remove();const r=document.createElement("div");r.textContent=e,r.classList.add("alerta",t);document.querySelector(o).appendChild(r),a&&setTimeout(()=>{r.remove()},4e3)}function mostrarResumen(){const e=document.querySelector(".contenido-resumen");for(;e.firstChild;)e.removeChild(e.firstChild);if(Object.values(cita).includes("")||0===cita.servicios.length)return void mostrarAlerta("Faltan datos de servicios, fecha u hora","error",".contenido-resumen",!1);const{nombre:t,fecha:o,hora:a,servicios:n}=cita,r=document.createElement("h3");r.textContent="Resumen de Servicios",e.appendChild(r),n.forEach(t=>{const{id:o,precio:a,nombre:n}=t,r=document.createElement("div");r.classList.add("contenedor-servicio");const c=document.createElement("p");c.textContent=n;const i=document.createElement("p");i.innerHTML="<span>Precio:</span> $"+a,r.appendChild(c),r.appendChild(i),e.appendChild(r)});const c=document.createElement("h3");c.textContent="Datos Cita",e.appendChild(c);const i=document.createElement("p");i.innerHTML="<span>Nombre:</span> "+t;const s=new Date(o),d=s.getDate()+2,l=s.getMonth(),u=s.getFullYear(),m=new Date(Date.UTC(u,l,d)).toLocaleDateString("es-MX",{weekday:"long",year:"numeric",month:"long",day:"numeric"}),p=document.createElement("p");p.innerHTML="<span>Fecha:</span> "+m;const v=document.createElement("p");v.innerHTML=`<span>Hora:</span> ${a} Horas`;const h=document.createElement("button");h.classList.add("boton"),h.textContent="Reservar Cita",h.onclick=reservarCita,e.appendChild(i),e.appendChild(p),e.appendChild(v),e.appendChild(h)}const serverCitas="https://evening-escarpment-67357.herokuapp.com/api/citas";async function reservarCita(){const{fecha:e,hora:t,servicios:o,id:a}=cita,n=o.map(e=>e.id),r=new FormData;r.append("fecha",e),r.append("hora",t),r.append("usuarioId",a),r.append("servicios",n);try{const e=""+serverCitas,t=await fetch(e,{method:"POST",body:r});(await t.json()).resultado&&Swal.fire({icon:"success",title:"Cita creada",text:"Tu cita fue creada correctamente"}).then(()=>{window.location.reload()})}catch(e){Swal.fire({icon:"error",title:"Error",text:"Hubo un error al crear tu cita"})}}
//# sourceMappingURL=app.js.map
