document.addEventListener("DOMContentLoaded", function(){

    // particles
    const container = document.getElementById('particles');
    if(container){
        for(let i = 0; i < 22; i++){
            const dot = document.createElement('div');
            dot.className = 'dot';
            const size = Math.random() * 4 + 2;
            const blue = 100 + Math.floor(Math.random() * 155);

            dot.style.cssText = `
                width:${size}px;
                height:${size}px;
                left:${Math.random()*100}%;
                top:${80 + Math.random()*50}%;
                background: rgba(0,${blue},255,0.2);
                animation-duration:${Math.random()*10+7}s;
                animation-delay:${Math.random()*6}s;
            `;
            container.appendChild(dot);
        }
    }

    // password strength
    const pwInput = document.getElementById('pw');
    if(pwInput){
        pwInput.addEventListener('input', function(){
            let force = 0;

            if(this.value.length >= 8) force++;
            if(/[A-Z]/.test(this.value)) force++;
            if(/[a-z]/.test(this.value)) force++;
            if(/[0-9]/.test(this.value)) force++;
            if(/[\W]/.test(this.value)) force++;

            const bar = document.getElementById('force-bar');
            const text = document.getElementById('force-text');

            if(this.value.length === 0){
                bar.style.width = "0%";
                text.textContent = "";
            }
            else if(force <= 1){
                bar.style.width = "25%";
                bar.style.background = "#ff4d4d";
                text.textContent = "Faible";
            }
            else if(force <= 3){
                bar.style.width = "60%";
                bar.style.background = "#ffaa00";
                text.textContent = "Moyen";
            }
            else{
                bar.style.width = "100%";
                bar.style.background = "#00d4ff";
                text.textContent = "Fort ✓";
            }
        });
    }

});

// show password (لازم تبقى global)
function togglePw(){
    const pw = document.getElementById('pw');
    if(pw){
        pw.type = pw.type === 'password' ? 'text' : 'password';
    }
    
}

