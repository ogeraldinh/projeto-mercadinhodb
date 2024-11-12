function toggleNovoCliente(select){
  var form = document.getElementById('novo_cliente_form');
  if (select.value === 'novo_cliente'){
      form.style.display = 'block';
  } else {
      form.style.display = 'none';
  }

}

function mascaraCPF(campo) {
  let cpf = campo.value.replace(/\D/g, '') // Remove tudo que não for número
  let cpfFormatado = ""

  // Aplicando a máscara do CPF
  for (let i = 0; i < cpf.length; i++) {
      if (i === 3 || i === 6) {
          cpfFormatado += "." // Adiciona o ponto
      } else if (i === 9) {
          cpfFormatado += "-" // Adiciona o hífen
      }
      cpfFormatado += cpf[i]
  }
  campo.value = cpfFormatado
}

// Função para aplicar a máscara de telefone
function mascaraTelefone(campo) {
  // Remove tudo o que não for número
  var telefone = campo.value.replace(/\D/g, '');

  // Se o número for menor que 2, apenas coloca o DDD
  if (telefone.length <= 2) {
      campo.value = `(${telefone}`;
  }
  // Se o número for entre 3 e 6, coloca o DDD e parte do número
  else if (telefone.length <= 6) {
      campo.value = `(${telefone.substring(0, 2)}) ${telefone.substring(2)}`;
  }
  // Se o número for maior que 6, coloca o DDD e o número completo no formato (00) 0 0000-0000
  else {
      campo.value = `(${telefone.substring(0, 2)}) ${telefone.substring(2, 3)} ${telefone.substring(3, 7)}-${telefone.substring(7, 11)}`;
  }
}