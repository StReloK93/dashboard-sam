import{d as i,N as d,u as p,c as u,a as e,w as c,f as n,v as l,o as m}from"./app-Hhb2Pc0F.js";const b={class:"flex justify-center items-center"},f={class:"w-[525px] mx-auto text-center rounded-lg relative overflow-hidden p-10"},x={class:"mb-6"},_={class:"mb-6"},g=e("div",{class:"mb-10"},[e("input",{type:"submit",value:"Sign In",class:"w-full rounded border border-primary p-3 bg-primary text-base text-white cursor-pointer hover:bg-opacity-90 transition"})],-1),y=i({__name:"Login",setup(h){const o=d({login:null,password:null}),r=p();function a(){r.login(o)}return(v,t)=>(m(),u("section",b,[e("div",f,[e("form",{onSubmit:c(a,["prevent"])},[e("div",x,[n(e("input",{type:"text","onUpdate:modelValue":t[0]||(t[0]=s=>o.login=s),placeholder:"Login",class:"w-full rounded border p-3 bg-[#FCFDFE] text-base text-body-color placeholder-[#ACB6BE] outline-none"},null,512),[[l,o.login]])]),e("div",_,[n(e("input",{type:"password","onUpdate:modelValue":t[1]||(t[1]=s=>o.password=s),placeholder:"Parol",class:"w-full rounded border p-3 bg-[#FCFDFE] text-base text-body-color placeholder-[#ACB6BE] outline-none"},null,512),[[l,o.password]])]),g],32)])]))}});export{y as default};