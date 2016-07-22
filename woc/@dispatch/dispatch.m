function varargout = dispatch (varargin)
  varargout = cell (nargout, 1);
  [ varargout{:} ] = __dispatch__ (varargin{:});

endfunction
