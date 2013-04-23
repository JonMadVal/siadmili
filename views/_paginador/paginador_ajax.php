<?php if (isset($this->_paginacion)): ?>
    <div class='row-fluid'>
        <div class="span5">
            <ul>
                <?php if ($this->_paginacion['primero']) : ?>
                    <li><a class="pagina" href="javascript:void(0);" data-page="<?php echo $this->_paginacion['primero']; ?>" title="Primero">&Lt;</a></li>
                <?php else : ?>
                    <li class="disabled"><span>&Lt;</span></li>
                <?php endif; ?>

                <?php if ($this->_paginacion['anterior']) : ?>
                    <li><a class="pagina" href="javascript:void(0);" data-page="<?php echo $this->_paginacion['anterior']; ?>" title="Anterior">&LT;</a></li>
                <?php else : ?>
                    <li class="disabled"><span>&LT;</span></li>
                <?php endif; ?>

                <?php for ($i = 0; $i < count($this->_paginacion['rango']); $i++): ?>
                    <?php if ($this->_paginacion['actual'] == $this->_paginacion['rango'][$i]): ?>
                        <li class="active"><span><?php echo $this->_paginacion['rango'][$i]; ?></span></li>
                    <?php else: ?>
                        <li><a class="pagina" href="javascript:void(0);" data-page="<?php echo $this->_paginacion['rango'][$i]; ?>" title="<?php echo $this->_paginacion['rango'][$i]; ?>"><?php echo $this->_paginacion['rango'][$i]; ?></a></li>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($this->_paginacion['siguiente']) : ?>
                    <li><a class="pagina" href="javascript:void(0);" data-page="<?php echo $this->_paginacion['siguiente']; ?>" title="Siguiente">&GT;</a></li>
                <?php else : ?>
                    <li class="disabled"><span>&GT;</span></li>
                <?php endif; ?>

                <?php if ($this->_paginacion['ultimo']) : ?>
                    <li><a class="pagina" href="javascript:void(0);" data-page="<?php echo $this->_paginacion['ultimo']; ?>" title="&Uacute;ltimo">&Gt;</a></li>
                <?php else : ?>
                    <li class="disabled"><span>&Gt;</span></li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="span7">
            <form class="form-search">
                <div class="input-append">
                    <input type="text" class="span3 search-query goto">
                    <button type="submit" id='goto_btn' class="btn">Ir...</button>
                </div>

                <label class="control-label" for="registros">Registros por pagina: </label>
                <select id="registros" class="span2">
                    <?php for ($i = 1; $i <= 3; $i += 1): ?>
                        <option value="<?php echo $i; ?>" <?php
                        if ($i == $this->_paginacion['limite']) {
                            echo 'selected="selected"';
                        }
                        ?>  ><?php echo $i; ?></option>
                            <?php endfor; ?>
                </select>
                <span class="total" data-total="<?php echo $this->_paginacion['total'] ?>">P&aacute;gina <strong><?php echo $this->_paginacion['actual'] ?></strong> de <strong><?php echo $this->_paginacion['total'] ?></strong></span>
            </form>
        </div>
    </div>
<?php endif; ?>