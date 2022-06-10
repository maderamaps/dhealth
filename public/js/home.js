var non_racikan = []; 
var racikan = []; 


$(document).ready(function() {
    $( "#type" ).change(function() {
        if($('#type').val() == 'Non Racikan'){
            $('#btn-tambah').hide('fast')
            $('#div_name').hide('fast')
            $('#obat_index').val(1)
            html=`<div id="div_tabmahan_obat"></div>`
            $( "#body_tambahan_obat" ).html( html );

        }else{
            $('#btn-tambah').show('fast')
            $('#div_name').show('fast')
        }
    });

    $(document).on("change", ".select-obat", function(){
        stok = $(this).find(':selected').data('stok')
        $(this).attr('data-stok',stok)
      });


    $(document).on("change", ".input-qyt", function(){
        index = $(this).attr('data-index')
        stok = parseInt($('#obat'+index).attr('data-stok'))
        input = parseInt($(this).val())
        console.log(index+stok+input)
        if(input > stok){
            alert('Stok Tidak Cukup, Sisa stok ada '+stok)
            $(this).val('')
        }
    });

    

});

window.add_field_obat = function (){
    index = $('#obat_index').val()
    index++;
    $('#obat_index').val(index)
    temp = $("#obat1").html();

    html=`
            <div class="row col-md-12" id="div_obat_index`+index+`" style="padding: 0px; margin: 0px; display:none">
                <div class="col-md-11" style="padding-left: 0px; margin: 0px">
                    <div class="form-group">
                        <label for="obat">Obat</label>
                        <select class="form-control selectpicker select-obat" data-stok="" data-index="`+index+`" id="obat`+index+`" nama="obat[]" required>
                            `+temp+`
                        </select>
                    </div>
                </div>
                <div class="col-md-1" style="padding: 0px">
                    <div class="form-group" style="w">
                        <label for="qyt_obat">qyt</label>
                        <input class="form-control selectpicker input-qyt" type="number" data-index="`+index+`" id="qyt_obat`+index+`" value="" style="height: 28px" required >
                    </div>
                </div>
            </div>
    `
    $( "#div_tabmahan_obat" ).before( html );
    $( "#div_obat_index"+index ).show('fast');
    $( "#btn_delete_obat" ).show('fast');
    setTimeout(function(){ 
        $('select.selectpicker').select2();
    }, 200);
    

}

window.delete_field_obat = function (){
    index = $('#obat_index').val()
    temp = index - 1;
    $('#obat_index').val(temp)
    $('#div_obat_index'+index).hide("fast")
    setTimeout(function(){ 
        $('#div_obat_index'+index).remove()
    }, 200);
    

    

    if(temp == 1){
        $( "#btn_delete_obat" ).hide('fast');
    }
}

$( "#form_resep" ).submit(function( event ) {
    
    Swal.fire({
        icon: 'success',
        width: '200px',
        showConfirmButton: false,
        timer: 1500
      })

    $('#btn-submit').show();

    if($('#type').val() == 'Non Racikan'){
        obat = $('#obat1').val()
        obat_id = $('#obat1').find(':selected').data('id')
        qyt_obat = $('#qyt_obat1').val()
        signa = $('#signa').val()
        signa_id = $('#signa').find(':selected').data('id')
        non_racikan[non_racikan.length] = {obat : obat, obat_id : obat_id, qyt_obat : qyt_obat, signa:signa, signa_id : signa_id};
        console.log(non_racikan) 
        set_table_non_racikan();

    }else{
        obat = [];
        obat_id = [];
        qyt = [];
        signa = $('#signa').val()
        signa_id = $('#signa').find(':selected').data('id')
        nama = $('#name').val()
        length = racikan.length;
        racikan[length] ={signa: signa, nama: nama, signa_id: signa_id}
        for(i=1;i<=$('#obat_index').val();i++){
            obat.push($('#obat'+i).val());
            racikan[length].obat=obat;

            obat_id.push($('#obat'+i).find(':selected').data('id'));
            racikan[length].obat_id=obat_id;

            qyt.push($('#qyt_obat'+i).val());
            racikan[length].qyt_obat=qyt;
        }

        console.log(racikan) 
        set_table_racikan()

    }


  });

  function set_table_non_racikan(){
    html = `
        <h4 style="text-decoration: underline">Non Racikan</h4>
        <table style="width: 100%">
            <tr>
                <th>NAMA OBAT</th>
                <th style="width:10%">QYT</th>
            </tr>
    `

    for(i=0;i<non_racikan.length;i++){
        html+=` <tr>
                    <td><b>`+non_racikan[i].obat+`</b></td>
                    <td>`+non_racikan[i].qyt_obat+`X</td>
                </tr>
                <tr>
                    <td style="padding-bottom: 10px">`+non_racikan[i].signa+`</td>
                    <td>
                        <a class="remove-non-racikan" data-index="`+i+`">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="fill:red; width:20px"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M0 256C0 114.6 114.6 0 256 0C397.4 0 512 114.6 512 256C512 397.4 397.4 512 256 512C114.6 512 0 397.4 0 256zM168 232C154.7 232 144 242.7 144 256C144 269.3 154.7 280 168 280H344C357.3 280 368 269.3 368 256C368 242.7 357.3 232 344 232H168z"/></svg>
                        </a>
                    </td>
                </tr>`
    }
   
    html += `</table>`
    
    $('#div_non_racikan').html(html);
}


function set_table_racikan(){
    html = `
        <h4 style="text-decoration: underline">Racikan</h4>
        <table style="width: 100%">
            <tr>
                <th>NAMA OBAT</th>
                <th style="width:10%">QYT</th>
            </tr>
    `


    for(j=0;j<racikan.length;j++){
        for(i=0;i<racikan[j].obat.length;i++){
            html+=` <tr>
                        <td><b>`+racikan[j].obat[i]+`</b></td>
                        <td>`+racikan[j].qyt_obat[i]+`X</td>
                    </tr>
                `
        }

        html+=` <tr>
                    <td style="padding-bottom: 10px; border-bottom: 1px solid rgba(0, 0, 0, 0.070)">`+racikan[j].signa+` - `+racikan[j].nama+`</td>
                    <td>
                        <a class="remove-racikan" data-index="`+j+`">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="fill:red; width:20px"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M0 256C0 114.6 114.6 0 256 0C397.4 0 512 114.6 512 256C512 397.4 397.4 512 256 512C114.6 512 0 397.4 0 256zM168 232C154.7 232 144 242.7 144 256C144 269.3 154.7 280 168 280H344C357.3 280 368 269.3 368 256C368 242.7 357.3 232 344 232H168z"/></svg>
                        </a>
                    </td>
                </tr>
         `
    }

    html+=`</table>`;

    $('#div_racikan').html(html);
}

$(document).on("click", ".remove-racikan", function(){
    index = $(this).attr('data-index');
    racikan.splice(index, 1);
    console.log(index);
    console.log(racikan);
    set_table_racikan();
});

$(document).on("click", ".remove-non-racikan", function(){
    index = $(this).attr('data-index');
    non_racikan.splice(index, 1);
    console.log(index);
    console.log(non_racikan);
    set_table_non_racikan();
});

$(document).on("click", "#btn-submit", function(){
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url:"submit_resep",
        type: "post",
        dataType: "json",
        data: {
            racikan : racikan,
            non_racikan: non_racikan,
            _token: CSRF_TOKEN
        },
        success: function(dataResult){
            Swal.fire({
                icon: 'success',
                showConfirmButton: false,
                timer: 1500,
                width: '100px',
            })
           
        }
    })
});

